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
    function($file)
    {
        if($file->isDir()) {
            // return true;
        }

        if ($file->getExtension() === 'php') {
            return
                preg_match('/(Bundle|Extension|Repository)$/', $file->getBasename('.php')) === 0
                &&
                preg_match('#/(DependencyInjection)/#', $file->getPathname()) === 0
            ;
        }

        return false;
    }
);
$report->addField($coverageField);
$script->noCodeCoverageForNamespaces(
    'Doctrine',
    'Symfony'
);


///////////////
// BOOTSTRAP //
///////////////
$runner->setBootstrapFile(__DIR__ . '/.atoum.bootstrap.php');


///////////////////////
// DEFAULT DIRECTORY //
///////////////////////
$runner->addTestsFromDirectory(__DIR__ . '/src/Mattlab/MuseBundle/Tests/Units');