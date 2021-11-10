<?php

namespace App;

class Request
{
    /**
     * @var string
     */
    private $operation;
    /**
     * @var mixed
     */
    private $operationId;
    /**
     * @var mixed
     */
    private $name;
    /**
     * @var mixed
     */
    private $password;
    /**
     * @var mixed
     */
    private $type;
    /**
     * @var mixed
     */
    private $posUser;
    /**
     * @var mixed
     */
    private $posId;
    /**
     * @var int
     */
    private $parameters;
    /**
     * @var float
     */
    private $amount;

    public function __construct(array $params)
    {
        $this->operation = $params["operation"];
        $this->operationId = (int)$params["operationId"];
        $this->name = $params["name"];
        $this->password = $params["password"];
        $this->type = $params["type"];
        $this->posId = (int)$params["posid"];
        $this->posUser = $params["posuser"];
        $this->amount = $params["amount"];
        $this->parameters = $params["parameters"];
    }

    /**
     * @return string
     */
    public function getOperation(): string
    {
        return $this->operation;
    }

    /**
     * @return int
     */
    public function getOperationId(): int
    {
        return $this->operationId;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @return mixed
     */
    public function getPosUser()
    {
        return $this->posUser;
    }

    /**
     * @return int
     */
    public function getPosId(): int
    {
        return $this->posId;
    }

    /**
     * @return bool Valida user&pass
     */
    public function validateUserPass(): bool
    {
        if ($this->name == 'admin' && $this->password == 'password') {
            return true;
        }

        return false;
    }

    public function getAmount()
    {
        return $this->amount;
    }
}