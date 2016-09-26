# PHPagstract

## Introduction

this is a php port of the Pagstract Templating Framework, a XML template engine, developed by 'freiheit.com'



## What is (PH)Pagstract?

(from org. docs in german)

Pagstract ist eine einfache Templating-Sprache, die von 'freiheit.com' entwickelt wurde. Dabei wurde bewusst keine vollständige Programmiersprache geschaffen, sondern sich auf einfach Konstrukte, die bei der Anzeige notwendig sind, beschränkt. Dadurch bleibt die Geschäftslogik von der reinen Anzeigelogik getrennt.
In einem typischen Anwendungsfall, stellt die Anwendung die notwendigen Daten als PageModel bereit. Diese werden an ein Pagstract-Template übergeben, dass diese Daten dann in einem schönen Layout einbettet und aufbereitet.



### WIP (work in progress)

done so far:

	* generic tokenizing
	* markup/pagstract tokenizing and symbolizing, creating abstract symbol tree
	* reference resolving and symbolizing, abstract data properties
    * (theme inheriting) filename/path resolving
	 
	 
still to do:

    * symbolize special data/component properties
    * final page assembly
    


## Installation

Just checkout the repository and use the classes as you desire...

    git clone https://gitlab.bjoernbartels.earth/php/phpagtract.git


Another alternative for downloading the project is to grab it via `curl`, and
then pass it to `tar`:

    cd my/project/dir
    curl -#L https://gitlab.bjoernbartels.earth/php/phpagtract/repository/archive.tar.gz?ref=master | tar xz --strip-components=1



## Status

[![Travis CI Build Status](https://travis-ci.org/bb-drummer/phpagstract.svg?branch=master)](https://travis-ci.org/bb-drummer/phpagstract)

[![GitLab.com Build Status](https://gitlab.com/php.bjoernbartels.earth/phpagstract/badges/master/build.svg)](https://gitlab.com/php.bjoernbartels.earth/phpagstract/commits/master)

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/bb-drummer/phpagstract/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/bb-drummer/phpagstract/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/bb-drummer/phpagstract/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/bb-drummer/phpagstract/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/bb-drummer/phpagstract/badges/build.png?b=master)](https://scrutinizer-ci.com/g/bb-drummer/phpagstract/build-status/master)

[![Code Climate](https://codeclimate.com/github/bb-drummer/phpagstract/badges/gpa.svg)](https://codeclimate.com/github/bb-drummer/phpagstract)

[![CII Best Practices](https://bestpractices.coreinfrastructure.org/projects/398/badge)](https://bestpractices.coreinfrastructure.org/projects/398)


## LICENSE

please see LICENSE file in project's root directory



## COPYRIGHT

&copy; 2016 [Björn Bartels], coding@bjoernbartels.earth, all rights reserved.


