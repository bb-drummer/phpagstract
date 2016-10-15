<?php
/**
 * page generator abstract
 */
namespace PHPagstract\Page;

use PHPagstract\Page\Model\PageModel;
use PHPagstract\Page\Model\PageModelAbstract;
use PHPagstract\Traits\ResourcesConfigurationTrait;
use PHPagstract\Traits\ThemeConfigurationTrait;
use PHPagstract\Streams\DataStream;
use PHPagstract\Streams\InputStream;
use PHPagstract\Symbol\PropertyReferenceSymbolizer;
use PHPagstract\Traits\PropertyResolverAwareTrait;
use PHPagstract\Traits\FilepathResolverAwareTrait;
use PHPagstract\Renderer\Renderer;
use PHPagstract\Page\Config\PageConfig;
use PHPagstract\AbstractConfiguration;
use PHPagstract\Renderer\RendererAbstract;

/**
 * page generator object abstract
 *
 * retrieves generator configuration, stores input-stream and data-stream, aka 
 * the template ans property tree, initialze the page-model, render output
 * 
 * @package   PHPagstract
 * @author    Björn Bartels <coding@bjoernbartels.earth>
 * @link      https://gitlab.bjoernbartels.earth/php/phpagstract
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @copyright copyright (c) 2016 Björn Bartels <coding@bjoernbartels.earth>
 */
abstract class PageAbstract
{
    use ThemeConfigurationTrait;
    use ResourcesConfigurationTrait;
    use PropertyResolverAwareTrait;
    use FilepathResolverAwareTrait;
    
    //
    // "config" vars
    //

    /**
     * template markup
     *
     * @var InputStream
     */
    public $inputStream = null;
    
    /**
     * template's assigned data
     *
     * @var DataStream
     */
    public $dataStream = null;

    /**
     * throw exception on error?
     *
     * @var boolean
     */
    public $throwOnError = false;
    
    
    
    //
    // related objects
    //
    
    /**
     * page model instance
     *
     * @var PageConfig
     */
    public $configuration = null;
    
    /**
     * page model instance
     *
     * @var PageModelAbstract
     */
    public $pageModel = null;
    
    /**
     * renderer instance
     *
     * @var Renderer
     */
    public $renderer = null;
    
    /**
     * property symbolizer
     *
     * @var PropertyReferenceSymbolizer
     */
    public $propertySymbolizer = null;
    
    
    
    /**
     * class constructor 
     * 
     * @param boolean $throwOnError throw exception on error?
     */
    public function __construct($throwOnError = false) 
    {
        $this->throwOnError = !!($throwOnError);
        // $this->getConfiguration()->set('throwOnError', $this->throwOnError);
        $this->getConfiguration()->throwOnError($this->throwOnError);
    }

    /**
     * initialize page model instance
     *
     * @return PageModelAbstract $pageModel instance
     */
    public function initPageModel() 
    {
        
        $pageModel = new PageModel($this, $this->throwOnError); 
        $this->setPageModel($pageModel);
        
        return $this->getPageModel();
    }
    
    /**
     * get current page-model instance
     * 
     * @return PageModelAbstract $pageModel instance
     */
    public function getPageModel() 
    {
        if (!($this->pageModel instanceof PageModelAbstract)) {
            $this->initPageModel();
        }
        return $this->pageModel;
    }

    /**
     * set the $pageModel instance
     * 
     * @param PageModelAbstract $pageModel instance
     */
    public function setPageModel($pageModel) 
    {
        $this->pageModel = $pageModel;
    }



    /**
     * get current renderer instance
     *
     * @return RendererAbstract $renderer instance
     */
    public function getRenderer()
    {
        if (!($this->renderer instanceof RendererAbstract)) {
            $this->renderer = new Renderer($this, $this->throwOnError);
        }
        return $this->renderer;
    }
    
    /**
     * set the $renderer instance
     * 
     * @param Renderer $renderer instance
     */
    public function setRenderer($renderer) 
    {
        $this->renderer = $renderer;
    }
    
    
    
    /**
     * retrieve curent input stream
     * 
     * @return string $inputStream
     */
    public function getInputStream() 
    {
        if (empty($this->inputStream)) {
            return '';
        }
        return $this->inputStream->getStream();
    }

    /**
     * set input stream, tries to resolve string to a file first and sets its 
     * content
     * 
     * @param  string $inputStream
     * @return self
     */
    public function setInputStream($inputStream) 
    {
        $filename = $this->getFilepathResolver()->resolveFilepath($inputStream);
        if (($filename !== null) && file_exists($filename)) {
            $fileContent = file_get_contents($filename);
            $inputStream = $fileContent;
        }
        $stream = new InputStream();
        $stream->setStream($inputStream);
        $this->inputStream = $stream;
        return $this;
    }
    
    

    /**
     * retrieve current data stream
     * 
     * @return mixed $dataStream
     */
    public function getDataStream() 
    {
        if (empty($this->dataStream)) {
            return null;
        }
        return $this->dataStream->getStream();
    }

    /**
     * create abstract property tree and set to data-stream
     * 
     * @param  string|array|\stdClass $dataStream
     * @return self
     */
    public function setDataStream($dataStream) 
    {
        // make sure, we will handle an object
        if (empty($dataStream)) {
            // create generic data object
            $dataStreamToSymbolize = (object) [ 
                "root" => (object) []    
            ];
        } else if (is_string($dataStream)) {
            // try to resolve toa filename and/or decode json string
            $filename = $this->getFilepathResolver()->resolveFilepath($dataStream);
            if (($filename !== null) && file_exists($filename)) {
                // file could be resolved
                $fileContent = file_get_contents($filename);
                $dataStreamToSymbolize = json_decode($fileContent);
            } else {
                // file could NOT be resolved
                if (file_exists($dataStream)) {
                    // ... but has been found otherwise
                    $fileContent = file_get_contents($dataStream);
                    $dataStreamToSymbolize = json_decode($fileContent);
                } else {
                    // ... or is just an ordenary (json) string
                    $dataStreamToSymbolize = json_decode($dataStream);
                }
            }
        } else if (is_array($dataStream)) {
            // create object from simple (assosative) array
            $dataStreamToSymbolize = (object) [ 
                "root" => (object) ($dataStream)   
            ];
        } else if (is_object($dataStream)) {
            // nothing special for now
            $dataStreamToSymbolize = ($dataStream);
        }
        
        // now we're sure to handle an object here check for the root property
        if (!property_exists($dataStreamToSymbolize, 'root') ) {
            $dataStreamToSymbolize = (object) [ 
                "root" => ($dataStreamToSymbolize)   
            ];
        }
        
        // create abstract property tree and store stream object
        $symbolizedData = $this->getPropertySymbolizer()->symbolize($dataStreamToSymbolize);
        $stream = new DataStream();
        $stream->setStream($symbolizedData);
        $this->dataStream = $stream;
        
        return $this;
    }
    
    
    
    /**
     * retrieve a property symbolizer instance
     * if not set, initialize new instance
     * 
     * @return PropertyReferenceSymbolizer $propertySymbolizer
     */
    public function getPropertySymbolizer() 
    {
        if (!($this->propertySymbolizer instanceof PropertyReferenceSymbolizer)) {
            $this->propertySymbolizer = new PropertyReferenceSymbolizer($this->throwOnError);
        }
        return $this->propertySymbolizer;
    }

    /**
     * set the property symbolizer instance
     * 
     * @param \PHPagstract\Symbol\PropertyReferenceSymbolizer $propertySymbolizer
     */
    public function setPropertySymbolizer($propertySymbolizer) 
    {
        $this->propertySymbolizer = $propertySymbolizer;
    }

    
    
    /**
     * retrieve a configuration object instance
     * if not set, initialize new instance with default settings
     * 
     * @return PageConfig $configuration
     */
    public function getConfiguration() 
    {
        if (!($this->configuration instanceof PageConfig)) {
            $this->configuration = new PageConfig();
        }
        return $this->configuration;
    }

    /**
     * set the page's configuration as array, stdClass or PageConfig object
     * 
     * @param array|PageConfig $configuration
     */
    public function setConfiguration($configuration) 
    {
        if (($configuration instanceof AbstractConfiguration) ) {
            $this->configuration = $configuration;
        } else if (is_array($configuration) ) {
            $this->getConfiguration()->setConfig($configuration);
        }
        return $this;
    }
    
    
    
    /**
     * create string output
     *
     * @return string
     */
    public function output() 
    {
        $model = $this->getPageModel();
        $processedInput = $model->process();

        $renderer = $this->getRenderer();
        $output = $renderer->render($processedInput);
        
        $output = trim($output);
        return $output;
    }


    
}

