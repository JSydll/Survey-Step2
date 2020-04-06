# Survey evaluator

This is meant as an extension for survey tools like LamaPoll providing instant personalized 
results for each participant. As these tools are sophisticated solutions for the configuration 
and management of online services, it does not make much sense to reinvent this wheel. However, 
offering a custom tailored result presentation after completing the survey could improve 
participation rates for online surveys (usually scoring lower participation than offline / 
personally guided polls).

## Some research on survey tools

Open Source tools:
- LimeSurvey

More open tools:
- LamaPoll

Plain commercial APIs:
- [SurveyMonkey API](https://developer.surveymonkey.com/api/v3/#collectors-id-responses-id-details)
- [SurveyHero API](https://developer.surveyhero.com/api/#get-all-answers-from-response)
- [Qualtrics API](https://api.qualtrics.com/reference#getresponse-1)

None of them offering a student plan & only premium plan has API access

## Design

The configuration, hosting and persistence of the surveys is done by the respective survey tool.


Survey Tool 
OUT: token <--> 
    Data Interface (derived for specifc tool, imports specific survey data) 
    IN: token OUT: rawData <--> 
        Evaluator (prepares data, runs calculator scripts) 
        IN: rawData data OUT: evalData <-->
            ResultGenerator (derived for specifc result, here: merges PDFs) 
            IN: evalData OUT: file name <-->
                ResultView (generated site for results) [index.php]
                IN: file name OUT: file name, email <-->
                    MailService (send result file)
                    IN: file name, email

- The application uses exceptions to abort the normal flow if errors occur.
- 

## Technologies & Tools

- PHP 7.2
- Composer ([Introduction](https://getcomposer.org/doc/00-intro.md))
- [libmergepdf](https://github.com/hanneskod/libmergepdf) (installed with `composer`)
- VS Code with phpfmt, PHP IntelliSense
- For accessing LimeSurvey's API, [jsonrpcphp](https://github.com/weberhofer/jsonrpcphp) is used (installed with `composer`) 

## LimeSurvey implementation

The last page of LimeSurvey needs to implement a link to this application with the URL parameters `?sid={SID]&response={SAVEDID}`. This leverages the LimeSurvey ExpressionManager syntax and makes the survey ID and individual response ID available for querying data. According to the [documentation of URL fields](https://manual.limesurvey.org/URL_fields), LimeSurvey can even automatically forward the user to the step2 application.

The implementation of the `IDataCollector` uses the [LimeSurvey RemoteControl API 2](https://manual.limesurvey.org/RemoteControl_2_API) to fetch the results of the participant by his response id.


## Todos

- ~~Use of Exceptions~~
- ~~Logging~~
- ~~Scripting of data processing & file generation~~
- ~~Can the questions be tagged in the full result?~~
- ~~Improve take-by-reference~~
- ~~Implement mailer~~
- Api implementation
- Separation of example and step2 code (into composer project)
- Implementation of client in example