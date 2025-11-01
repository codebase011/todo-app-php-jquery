<?php

class Todo
{
  // El id se genera automáticamente ya que es AUTO_INCREMENT
  private $id;
  // La fecha de creación se genera automáticamente en el constructor
  private $created_at;

  public function __construct(
    private $title,
    private $description,
  ) {
    $this->created_at = date('Y-m-d H:i:s');
  }

  // Getters
  public function getId()
  {
    return $this->id;
  }

  public function getTitle()
  {
    return $this->title;
  }

  public function getDescription()
  {
    return $this->description;
  }

  public function getCreatedAt()
  {
    return $this->created_at;
  }

  // Setters
  public function setId($id)
  {
    $this->id = $id;
  }

  public function setTitle($title)
  {
    $this->title = $title;
  }

  public function setDescription($description)
  {
    $this->description = $description;
  }
}