<?php
/**
 * @file
 *
 * @author Joschka Seydell
 * @date 07.03.2020
 */

/** @brief Global settings of step two */
$location = __DIR__;
$namespaceName = "Step2";

/** @brief Dependencies */
require_once "vendor/autoload.php";

/** @brief General includes */
require_once "Logger.php";
require_once "HttpException.php";
require_once "ExceptionHandler.php";

/** @brief Interfaces */
require_once "interfaces/IDataCollector.php";
require_once "interfaces/IProcessor.php";
require_once "interfaces/IResultGenerator.php";

/** @brief Implementations */
require_once "Executor.php";
require_once "RestApi.php";

require_once "schema/Validation.php";

require_once "modules/collectors/LimeSurveyCollector.php";
require_once "modules/processors/ScriptedProcessor.php";
require_once "modules/result-generators/FileGenerator.php";
