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

/** @brief Interfaces */
require_once "interfaces/IDataCollector.php";
require_once "interfaces/IProcessor.php";
require_once "interfaces/IResultGenerator.php";
require_once "interfaces/IScriptCallable.php";

/** @brief Implementations */
require_once "modules/Logger.php";
require_once "modules/HttpException.php";
require_once "modules/ExceptionHandler.php";
require_once "modules/Mailer.php";

require_once "schema/Validation.php";

require_once "Executor.php";
require_once "Api.php";

require_once "modules/collectors/LimeSurveyCollector.php";
require_once "modules/processors/ScriptedProcessor.php";
require_once "modules/result-generators/FileGenerator.php";
require_once "modules/result-generators/DataGenerator.php";