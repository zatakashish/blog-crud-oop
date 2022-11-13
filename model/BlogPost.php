<?php

namespace Model;

class BlogPost
{

    private string $id;
    private string $author;
    private string $title;
    private string $body;
    private string $createdOn;

    //Simple map for variable between database column names and class property name
    private const PROPERTY_MAP_SNAKE_TO_CAMEL = [
        'created_on' => 'createdOn'
    ];

    public function __construct(array $row = [])
    {
        foreach ($row as $key => $value) {
            if (method_exists(self::class, 'set' . ucfirst($key))) {
                $functionName = 'set' . ucfirst($key);
                $this->$functionName($value);
            } elseif (array_key_exists($key, self::PROPERTY_MAP_SNAKE_TO_CAMEL)) {
                $functionName = 'set' . ucfirst(self::PROPERTY_MAP_SNAKE_TO_CAMEL[$key]);
                $this->$functionName($value);
            }
        }
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * @param string $author
     */
    public function setAuthor(string $author): void
    {
        $this->author = $author;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @param string $body
     */
    public function setBody(string $body): void
    {
        $this->body = $body;
    }

    /**
     * @return string
     */
    public function getCreatedOn(): string
    {
        return $this->createdOn;
    }

    /**
     * @param string $createdOn
     */
    public function setCreatedOn(string $createdOn): void
    {
        $this->createdOn = $createdOn;
    }
}