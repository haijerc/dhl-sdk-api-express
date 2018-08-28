<?php

namespace Dhl\Express\Webservice\Soap\Type\DocumentRendering;

class DataType
{

    /**
     * @var FieldType[] $Field
     */
    protected $Field = null;

    /**
     * @var FieldListType[] $FieldList
     */
    protected $FieldList = null;


    public function __construct()
    {
    }

    /**
     * @return FieldType[]
     */
    public function getField(): array
    {
        return $this->Field;
    }

    /**
     * @param FieldType[] $Field
     * @return \Dhl\Express\Webservice\Soap\Type\DocumentRendering\DataType
     */
    public function setField(array $Field = null)
    {
        $this->Field = $Field;
        return $this;
    }

    /**
     * @return FieldListType[]
     */
    public function getFieldList(): array
    {
        return $this->FieldList;
    }

    /**
     * @param FieldListType[] $FieldList
     * @return \Dhl\Express\Webservice\Soap\Type\DocumentRendering\DataType
     */
    public function setFieldList(array $FieldList = null)
    {
        $this->FieldList = $FieldList;
        return $this;
    }
}