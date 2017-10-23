<?php
/**
 * Created by PhpStorm.
 * User: melas
 * Date: 10/18/17
 * Time: 12:18 PM
 */

class Validator
{
    private $error = false, $errors = [];
    private $db = null;

    /**
     * Validator constructor.
     * @param null $db
     */
    public function __construct()
    {
        $this->db = DB::getInstance();
    }

    public function validate($request,  $rules)
    {
        if(count($rules) > 0) {
            foreach ($rules as $field => $rule) {
                $criteria = explode('|', $rule);
                foreach ($criteria as $criterion) {
                    $criterion = explode(':', $criterion);

                    if($criterion[0] === 'required' && empty($request[$field])) {
                        $this->error = true;
                        $this->addError(
                            ucwords($field) . ' is required'
                        );
                    } else {
                        switch($criterion[0]) {
                            case 'min':
                                if($criterion[1] > strlen($request[$field])) {
                                    $this->error = true;
                                    $this->addError(
                                        ucfirst($field) . ' must be greater than ' . $criterion[1]
                                    );
                                }
                                break;
                            case 'max':
                                if($criterion[1] < strlen($request[$field])) {
                                    $this->error = true;
                                    $this->addError(
                                        ucfirst($field) . ' must be less than ' . $criterion[1]
                                    );
                                }
                                break;
                            case 'matches':
                                if($request[$criterion[1]] !== $request[$field]) {
                                    $this->error = true;
                                    $this->addError('Passwords don\'t match');
                                }
                                break;
                            case 'email':
                                if(!filter_var($request[$field], FILTER_VALIDATE_EMAIL)) {
                                    $this->error = true;
                                    $this->addError('Email is invalid');
                                }
                                break;
                            case 'unique':
                                if($this->db->get($criterion[1], array($field, '=' ,$request[$field]))->count()) {
                                    $this->error = true;
                                    $this->addError(ucfirst($field) . " already exists");
                                }
                                break;
                            case 'image':
                                if($_FILES[$field]["size"] > $criterion[1] * 1000000) {
                                    $this->error = true;
                                    $this->addError("Image size should not be above " . $criterion[1] . "MB");
                                }
                                break;
                        }
                    }
                }
            }
        }
    }

    public function fails()
    {
        return $this->error;
    }

    public function addError($error)
    {
        array_push($this->errors, $error);
    }

    public function errors()
    {
        return $this->errors;
    }
}