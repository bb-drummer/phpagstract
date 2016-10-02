<?php

namespace PHPagstract;

use PHPagstract\Page\PageModel;
use PHPagstract\Page\PageModelAbstract;
use PHPagstract\Page\Resolver\FilepathResolver;
use PHPagstract\Page\Resolver\PropertyResolver;
use PHPagstract\Page\ResourcesConfigurationTrait;
use PHPagstract\Page\ThemeConfigurationTrait;
use PHPagstract\Streams\DataStream;
use PHPagstract\Streams\InputStream;
use PHPagstract\Symbol\PropertyReferenceSymbolizer;

/**
 * page generator object abstract
 *
 * retrieves generator configuration, stores input-stream and data-stream, aka 
 * the template ans property tree, initialze the page-model
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
     * @var PageModelAbstract
     */
    public $pageModel = null;
    
    /**
     * file/path resolver
     *
     * @var FilepathResolver
     */
    public $filepathResolver = null;
    
    /**
     * property reference resolver
     *
     * @var PropertyResolver
     */
    public $propertyResolver = null;
    
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
     * @param \PHPagstract\Page\PageModelAbstract $pageModel
     */
    public function setPageModel($pageModel) 
    {
        $this->pageModel = $pageModel;
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
     * set input stream
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
     * set data stream
     * 
     * @param  string|array|\stdClass $dataStream
     * @return self
     */
    public function setDataStream($dataStream) 
    {
        if (empty($dataStream)) {
            $dataStream = (object) [ 
                "root" => (object) []    
            ];
        } else if (is_string($dataStream)) {
            $filename = $this->getFilepathResolver()->resolveFilepath($dataStream);
            if (($filename !== null) && file_exists($filename)) {
                // file could be resolved
                $fileContent = file_get_contents($filename);
                $dataStream = json_decode($fileContent);
            } else {
                // file could NOT be resolved
                if (file_exists($dataStream)) {
                    // ... but has been found otherwise
                    $fileContent = file_get_contents($dataStream);
                    $dataStream = json_decode($fileContent);
                } else {
                    // ... or is just an ordenary (json) string
                    $dataStream = json_decode($dataStream);
                }
            }
        } else if (is_object($dataStream)) {
            $dataStream = ($dataStream);
        }
        $symbolizedData = $this->getPropertySymbolizer()->symbolize($dataStream);
        $stream = new DataStream();
        $stream->setStream($symbolizedData);
        $this->dataStream = $stream;
        return $this;
    }

    /**
     * retrieve a file/path resolver instance
     * if not set, initialize new instance
     *
     * @return \PHPagstract\Page\Resolver\FilepathResolver $filepathResolver
     */
    public function getFilepathResolver() 
    {
        if (!($this->filepathResolver instanceof FilepathResolver)) {
            $this->filepathResolver = new FilepathResolver($this->throwOnError);
            $this->filepathResolver->setBaseDir($this->getBaseDir());
            $this->filepathResolver->setThemesDir($this->getThemesDir());
            $this->filepathResolver->setThemeId($this->getThemeId());
        }
        return $this->filepathResolver;
    }

    /**
     * set the file/path resolver instance
     * 
     * @param \PHPagstract\Page\Resolver\FilepathResolver $filepathResolver
     */
    public function setFilepathResolver($filepathResolver) 
    {
        $this->filepathResolver = $filepathResolver;
    }

    /**
     * retrieve a property resolver instance
     * if not set, initialize new instance
     *
     * @return \PHPagstract\Page\Resolver\PropertyResolver $propertyResolver
     */
    public function getPropertyResolver() 
    {
        if (!($this->propertyResolver instanceof PropertyResolver)) {
            $this->propertyResolver = new PropertyResolver(null, $this->throwOnError);
            $this->propertyResolver->setStream($this->getDataStream());
        }
        return $this->propertyResolver;
    }

    /**
     * set the property resolver instance
     * 
     * @param \PHPagstract\Page\Resolver\PropertyResolver $propertyResolver
     */
    public function setPropertyResolver($propertyResolver) 
    {
        $this->propertyResolver = $propertyResolver;
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

    
}

