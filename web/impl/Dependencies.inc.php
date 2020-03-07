<?php
/**
 * @file Imports all necessary dependencies for the implementation.
 *
 * @author Joschka Seydell
 * @date 07.03.2020
 */

// Interface definitions
require_once(__DIR__."/../interfaces/IDataInterface.php");
require_once(__DIR__."/../interfaces/IProcessor.php");
require_once(__DIR__."/../interfaces/IResultGenerator.php");

// Utility
require_once(__DIR__."/../utility/MailService.php");
require_once(__DIR__."/../utility/PdfMerger.php");

?>