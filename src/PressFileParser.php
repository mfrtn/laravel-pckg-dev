<?php

namespace mfrtn\Press;

use Carbon\Carbon;
use Illuminate\Support\Facades\File;

class PressFileParser
{
    protected $filename;

    protected $rawData;

    protected $data;

    public function __construct($filename)
    {
        $this->filename = $filename;

        $this->splitFile();

        $this->explodeData();

        $this->processFields();
    }

    public function getRawdata()
    {
        return $this->rawData;
    }

    public function getData()
    {
        return $this->data;
    }

    protected function splitFile()
    {
        preg_match(
            '/^\-{3}(.*?)\-{3}(.*)/s',
            File::exists($this->filename) ? File::get($this->filename) : $this->filename,
            $this->rawData
        );
    }

    protected function explodeData()
    {
        foreach (explode("\n", trim($this->rawData[1])) as $fieldString) {
            preg_match('/(.*):\s?(.*)/', $fieldString, $fieldArray);
            $this->data[$fieldArray[1]] = $fieldArray[2];
        }
        $this->data['body'] = trim($this->rawData[2]);
    }

    protected function processFields()
    {
        foreach ($this->data as $field => $value) {

            $class = 'mfrtn\\Press\Fields\\' . ucfirst($field);

            if (!class_exists($class) || !method_exists($class, 'process')) {
                $class = 'mfrtn\\Press\Fields\\Extra';
            }

            $this->data = array_merge(
                $this->data,
                $class::process($field, $value, $this->data),
            );
        }
    }
}
