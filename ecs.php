<?php
/*
 * This file is part of UCRM Plugin SDK.
 *
 * Copyright (c) 2022 Ubiquiti Inc.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

declare(strict_types=1);

use PhpCsFixer\Fixer\ClassNotation\ClassAttributesSeparationFixer;
use PhpCsFixer\Fixer\ClassNotation\SelfAccessorFixer;
use PhpCsFixer\Fixer\ControlStructure\YodaStyleFixer;
use PhpCsFixer\Fixer\Operator\ConcatSpaceFixer;
use PhpCsFixer\Fixer\Operator\UnaryOperatorSpacesFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocAnnotationWithoutDotFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocSummaryFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocToCommentFixer;
use PhpCsFixer\Fixer\ControlStructure\NoSuperfluousElseifFixer;
use PhpCsFixer\Fixer\ControlStructure\NoUselessElseFixer;
use PhpCsFixer\Fixer\Import\OrderedImportsFixer;
use PhpCsFixer\Fixer\Operator\NotOperatorWithSuccessorSpaceFixer;
use PhpCsFixer\Fixer\Phpdoc\NoSuperfluousPhpdocTagsFixer;
use PhpCsFixer\Fixer\ReturnNotation\NoUselessReturnFixer;
use PhpCsFixer\Fixer\StringNotation\SingleQuoteFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;

return static function (ECSConfig $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();

    $parameters->set('skip', [
        SelfAccessorFixer::class => null,
        YodaStyleFixer::class => null,
        UnaryOperatorSpacesFixer::class => null,
        PhpdocAnnotationWithoutDotFixer::class => null,
        PhpdocSummaryFixer::class => null,
        PhpdocToCommentFixer::class => null,
    ]);

    $services = $containerConfigurator->services();

    $services->set(NoSuperfluousElseifFixer::class);
    $services->set(NoUselessElseFixer::class);
    $services->set(OrderedImportsFixer::class);
    $services->set(ConcatSpaceFixer::class)
        ->call('configure', [
            [
                'spacing' => 'one',
            ],
        ]);

    $services->set(NotOperatorWithSuccessorSpaceFixer::class);
    $services->set(NoSuperfluousPhpdocTagsFixer::class);
    $services->set(NoUselessReturnFixer::class);
    $services->set(SingleQuoteFixer::class);
    $services->set(ClassAttributesSeparationFixer::class)
        ->call('configure', [
            [
                'elements' => [
                    'property' => 'only_if_meta',
                    'method' => 'one',
                ],
            ],
        ]);
};
