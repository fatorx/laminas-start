<?php 

namespace Tags\Controller;

use Laminas\InputFilter\InputFilter;
use Laminas\InputFilter\Input;
use Laminas\Validator;

class TagFilter 
{
    public function filter($data)
    {
        $email = new Input('email');
        $email->getValidatorChain()
            ->attach(new Validator\EmailAddress());

        $password = new Input('password');
        $password->getValidatorChain()
                ->attach(new Validator\StringLength([3, 15]));

        $inputFilter = new InputFilter();
        $inputFilter->add($email)
                    ->add($password)
                    ->setData($_POST);

        if ($inputFilter->isValid()) {
            echo "The form is valid\n";
        } else {
            echo "The form is not valid\n";
            foreach ($inputFilter->getInvalidInput() as $error) {
                print_r($error->getMessages());
            }
        }
    }
}