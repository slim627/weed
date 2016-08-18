<?php

namespace CommonBundle\Grid;

class GridColumn implements \JsonSerializable
{
    /**
     * Field name
     * @var string
     */
    protected $id;

    /**
     * Field title for table
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var array
     */
    protected $options;

    public function __construct($id, $title, $type = 'string', $options = [])
    {
        $this->id = $id;
        $this->title = $title;
        $this->type = $type;
        $this->options = $options;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function jsonSerialize()
    {
        return [
            'title'   => $this->getTitle(),
            'id'      => $this->getId(),
            'type'    => $this->getType(),
            'options' => $this->getOptions(),
        ];
    }
}