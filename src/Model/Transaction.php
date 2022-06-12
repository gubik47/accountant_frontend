<?php

namespace App\Model;

use DateTime;

class Transaction
{
    private int $id;

    private string $transactionId;

    private string $type;

    private float $amount;

    private string $currency;

    private ?DateTime $dateOfIssue = null;

    private ?DateTime $dateOfCharge = null;
    
    private ?string $description = null;
    
    private ?string $note = null;
    
    private ?string $variableSymbol = null;
    
    private ?string $constantSymbol = null;
    
    private ?string $specificSymbol = null;
    
    private ?string $counterPartyAccountName = null;
    
    private ?string $counterPartyAccountNumber = null;
    
    private ?string $location = null;
    
    private ?string $consigneeMessage = null;

    public function __construct(array $data)
    {
        $this->id = intval($data["id"]);
        $this->transactionId = strval($data["transaction_id"]);
        $this->type = strval($data["type"]);
        $this->amount = floatval($data["amount"]);
        $this->currency = strval($data["currency"]);

        if (isset($data["date_of_issue"])) {
             $date = DateTime::createFromFormat("Y-m-d", $data["date_of_issue"]);
             if ($date) {
                 $this->dateOfIssue = $date;
             }
        }

        if (isset($data["date_of_charge"])) {
            $date = DateTime::createFromFormat("Y-m-d", $data["date_of_charge"]);
            if ($date) {
                $this->dateOfCharge = $date;
            }
        }

        if (isset($data["description"])) {
            $this->description = strval($data["description"]);
        }

        if (isset($data["note"])) {
            $this->note = strval($data["note"]);
        }

        if (isset($data["variable_symbol"])) {
            $this->variableSymbol = strval($data["variable_symbol"]);
        }

        if (isset($data["constant_symbol"])) {
            $this->constantSymbol = strval($data["constant_symbol"]);
        }

        if (isset($data["specific_symbol"])) {
            $this->specificSymbol = strval($data["specific_symbol"]);
        }

        if (isset($data["counterparty_account_name"])) {
            $this->counterPartyAccountName = strval($data["counterparty_account_name"]);
        }

        if (isset($data["counterparty_account_number"])) {
            $this->counterPartyAccountNumber = strval($data["counterparty_account_number"]);
        }

        if (isset($data["location"])) {
            $this->location = strval($data["location"]);
        }

        if (isset($data["consignee_message"])) {
            $this->consigneeMessage = strval($data["consignee_message"]);
        }
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTransactionId(): string
    {
        return $this->transactionId;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getDateOfIssue(): DateTime|bool|null
    {
        return $this->dateOfIssue;
    }

    public function getDateOfCharge(): DateTime|bool|null
    {
        return $this->dateOfCharge;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function getVariableSymbol(): ?string
    {
        return $this->variableSymbol;
    }

    public function getConstantSymbol(): ?string
    {
        return $this->constantSymbol;
    }

    public function getSpecificSymbol(): ?string
    {
        return $this->specificSymbol;
    }

    public function getCounterPartyAccountName(): ?string
    {
        return $this->counterPartyAccountName;
    }

    public function getCounterPartyAccountNumber(): ?string
    {
        return $this->counterPartyAccountNumber;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function getConsigneeMessage(): ?string
    {
        return $this->consigneeMessage;
    }

    public function getLabel(): string
    {
        if ($this->counterPartyAccountName) {
            return $this->counterPartyAccountName;
        }

        if ($this->consigneeMessage) {
            return $this->consigneeMessage;
        }

        if ($this->description) {
            return $this->description;
        }

        if ($this->counterPartyAccountNumber) {
            return $this->counterPartyAccountNumber;
        }

        return $this->type;
    }
}