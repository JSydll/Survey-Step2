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
require_once "$location/vendor/autoload.php";

/** @brief Interfaces */
require_once "$location/interfaces/IDataCollector.php";
require_once "$location/interfaces/IProcessor.php";
require_once "$location/interfaces/IResultGenerator.php";
require_once "$location/interfaces/IScriptCallable.php";

/** @brief Implementations */
require_once "$location/modules/Logger.php";
require_once "$location/modules/HttpException.php";
require_once "$location/modules/ExceptionHandler.php";
require_once "$location/modules/Mailer.php";

require_once "$location/schema/Validation.php";

require_once "$location/Executor.php";
require_once "$location/Api.php";

require_once "$location/modules/collectors/LimeSurveyCollector.php";
require_once "$location/modules/processors/ScriptedProcessor.php";
require_once "$location/modules/result-generators/ScriptedGenerator.php";
require_once "$location/modules/result-generators/MapGenerator.php";
require_once "$location/modules/result-generators/FileGenerator.php";
require_once "$location/modules/ForwardingScript.php";