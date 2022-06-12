<?php

namespace App\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;

class TransactionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add("file", FileType::class, [
            "label" => "CSV file",
            "constraints" => [
                new File(["mimeTypes" => ["text/csv", "application/csv", "text/x-csv", "text/plain"], "maxSize" => "10M"])
            ]
        ]);
    }
}