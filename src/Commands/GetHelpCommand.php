<?php

namespace Bakgul\Kernel\Commands;

use Bakgul\Kernel\Helpers\Arry;
use Illuminate\Console\Command;

class GetHelpCommand extends Command
{
    protected $signature = 'get-help {from?}';
    protected $description = '';

    public function __construct()
    {
        parent::__construct();
    }

    private $max = 20;
    private $commandSignature;

    public function handle()
    {
        if (!$this->argument('from')) {
            foreach (array_keys($this->classes) as $key) {
                $this->error(
                    "{$this->indentation()}get-help {$key}" . str_repeat(' ', 30 - strlen($key))
                );
            }

            $this->newLine();

            return;
        }
        
        if (Arry::hasNot($this->argument('from'), $this->classes)) {
            return $this->error('Unknown command key');
        }

        $class = $this->getClass();

        if (!$class) return $this->noClass();

        $this->displayHelp($class);
    }

    private function getClass()
    {
        $namespace = $this->classes[$this->argument('from')]['namespace'];

        return class_exists($namespace) ? new $namespace : null;
    }

    private function noClass()
    {
        $p = $this->classes[$this->argument('from')]['package'];

        $this->error(implode(' ', [
            "No command has been found with the given key.",
            "Make sure you installed {$p}",
            "from https://github.com/bulentAkgul/{$p}."
        ]));
    }

    private function displayHelp($class)
    {
        $help = $class->getCommandHelp();

        $this->displayDescription($help['description']);
        $this->displaySignature($help['signature']);
        $this->displayArguments($help['arguments']);
        $this->displayOptions($help['options']);
        $this->displayExamples($help['examples']);
    }

    private function displayDescription($description): void
    {
        $this->title('description');

        $this->write($description, 1, 0);

        $this->newLine();
    }

    private function displaySignature($signature): void
    {
        $this->title('signature');

        if (count($signature) == 1) {
            $this->write($signature[0], 1, 0);
            $this->newLine();
            return;
        }

        foreach ($this->getSignatureEntries($signature) as $entry) {
            $this->writeHeadedLine($entry[0], $entry[1]);
        }

        $this->newLine();
    }

    private function displayArguments($arguments): void
    {
        $this->title('arguments');

        foreach ($arguments as $arg => $desc) {
            $this->writeHeadedLine($arg, $desc[0]);

            array_shift($desc);

            foreach ($desc as $part => $lines) {
                if (is_string($part)) {
                    $this->warn($this->head($part) . str_repeat('-', 50));
                }

                if (is_string($lines)) {
                    $this->write($lines);
                    continue;
                }

                foreach ($lines as $line) {
                    $this->write($line);
                }
            }
            $this->newLine();
        }

        $this->newLine();
    }

    private function displayOptions($options): void
    {
        $this->title('options');

        foreach ($options as $opt => $desc) {
            $this->writeHeadedLine($opt, $desc[0]);

            array_map(fn ($x) => $this->write($x), Arry::drop($desc, 'F'));

            $this->newLine();
        }

        $this->newLine();
    }

    private function displayExamples($examples): void
    {
        if (!$examples) return;
        
        $this->title('examples');

        foreach ($examples as $example) {
            foreach (explode(' | ', $example) as $i => $part) {
                $this->line("{$this->indentation($i + 1)}{$part}");
            }
        }

        $this->newLine();
    }

    private function getSignatureEntries($signature)
    {
        foreach ($signature as $part) {
            $entry = explode(' : ', str_replace(['{', '}'], '', $part));

            $entry[0] = array_reverse(explode('|', $entry[0]))[0];
            $entry[1] = trim(Arry::get($entry, 1) ?? '');

            $this->commandSignature[] = $entry;
        }

        return $this->commandSignature;
    }

    private function title($title)
    {
        $this->comment(ucfirst($title) . ':');
    }

    private function head($head, $indentation = 0.5)
    {
        return str_repeat(' ', $this->max - strlen($head))
            . $head
            . $this->indentation($indentation);
    }

    private function indentation($repeat = 1)
    {
        return str_repeat(' ', $repeat * 4);
    }

    private function writeHeadedLine($head, $tail = '')
    {
        $this->line("<info>{$this->head($head)}</info>{$tail}");
    }

    private function write($line, $indentation = 0.5, $max = null)
    {
        $this->line($this->indentation($indentation) . str_repeat(' ', $max ?? $this->max) . $line);
    }

    private $classes = [
        'file' => [
            'namespace' => '\Bakgul\FileCreator\Commands\CreateFileCommand',
            'package' => 'laravel-file-creator',
        ],
        'package' => [
            'namespace' => '\Bakgul\PackageGenerator\Commands\CreatePackageCommand',
            'package' => 'laravel-package-generator',
        ],
        'relation' => [
            'namespace' => '\Bakgul\CodeGenerator\Commands\GenerateRelationshipCommand',
            'package' => 'laravel-code-generator',
        ],
        'resource' => [
            'namespace' => '\Bakgul\ResourceCreator\Commands\CreateResourceCommand',
            'package' => 'laravel-resource-creator',
        ],
    ];
}
