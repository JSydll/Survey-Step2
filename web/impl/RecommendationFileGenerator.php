<?php
/**
 * @file Implements generation of a recommendations file
 * 
 * @author Joschka Seydell
 * @date 07.03.2020
 */
require_once("Dependencies.inc.php");

class RecommendationFileGenerator implements IResultGenerator 
{
    public function GenerateFile($evaluatedData) : string
    {
        echo "RecommendationFileGenerator got as evaluated data:<br>";
        foreach($evaluatedData as $key => $val)
        {
            echo $key.": ".$val."<br>";
        }
        return "some/temporary/file.pdf";
    }
}
?>