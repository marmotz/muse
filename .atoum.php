<?php

use \mageekguy\atoum;

$report = $script->addDefaultReport();


///////////
// LOGOS //
///////////
$report->addField(new atoum\report\fields\runner\result\logo());


//////////////
// COVERAGE //
//////////////
$coveragePath = __DIR__ . '/.coverage';

if(!file_exists($coveragePath)) {
    mkdir($coveragePath);
}

$coverageField = new atoum\report\fields\runner\coverage\html('Muse', $coveragePath);
$coverageField->setRootUrl('http://hynea.mattlab.com/muse/.coverage');
$coverageField->addSrcDirectory(
    __DIR__ . '/src/Mattlab',
    function($file) {
        if($file->isDir()) {
            return true;
        }

        if($file->getExtension() === 'php'
        && preg_match('/Bundle$/', $file->getBasename('.php')) === 0) {
            return true;
        }

        return false;
    }
);
$report->addField($coverageField);
$script->noCodeCoverageForNamespaces(
    'Doctrine',
    'Symfony',
    'mageekguy',
    'Mattlab\MuseBundle\Adapter',
    'Mattlab\MuseBundle\DependencyInjection',
    'Mattlab\MuseBundle\Controller',
    'Mattlab\MuseBundle\Entity\Base',
    'Mattlab\MuseBundle\Repository'
);


///////////////
// BOOTSTRAP //
///////////////
$runner->setBootstrapFile(__DIR__ . '/.atoum.bootstrap.php');


///////////////////////
// DEFAULT DIRECTORY //
///////////////////////
if(count(array_intersect($_SERVER['argv'], array('-f', '--files', '-d', '--directories'))) === 0) {
    $runner->addTestsFromDirectory(__DIR__ . '/src/Mattlab/MuseBundle/Tests/Units');
}