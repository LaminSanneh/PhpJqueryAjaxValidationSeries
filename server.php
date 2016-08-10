<?php
$values = $_POST["formData"];

$rules = [
    "name" => "required",
    "email" => "required|email",
    "password" => "required|min:3|max:10"
];

function validate ($values, $rules) {
    $errors = [];
    $valid = true;

    foreach ($rules as $formKey => $rule) {
        $splitRules = explode("|", $rule);
        $errors[$formKey] = [];

        foreach ($splitRules as $splitRule) {
            $splitRuleWithOptions = explode(":", $splitRule);

            switch ($splitRuleWithOptions[0]) {
                case 'required':
                    if(!isset($values[$formKey]) || empty($values[$formKey]) || strlen($values[$formKey]) == 0) {
                        $errors[$formKey][] = $formKey . " is required";
                    }
                    break;

                case 'email':
                    if(!filter_var($values[$formKey], FILTER_VALIDATE_EMAIL)) {
                        $errors[$formKey][] = $formKey . " must be in an email format";
                    }
                    break;

                case 'min':
                    if(strlen($values[$formKey]) < $splitRuleWithOptions[1]) {
                        $errors[$formKey][] = $formKey . " must be atleast " . $splitRuleWithOptions[1] . " in length";
                    }
                    break;

                case 'max':
                    if(strlen($values[$formKey]) > $splitRuleWithOptions[1]) {
                        $errors[$formKey][] = $formKey . " must be atmost " . $splitRuleWithOptions[1] . " in length";
                    }
                    break;
            }
        }
    }

    foreach ($errors as $key => $value) {
        if(count($value) > 0) {
            $valid = false;
        }
    }

    return [$valid, $errors];
}

$results = validate($values, $rules);

echo json_encode($results);
?>