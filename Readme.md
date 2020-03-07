# Survey evaluator

This is meant as an extension for survey tools like LamaPoll providing instant personalized 
results for each participant. As these tools are sophisticated solutions for the configuration 
and management of online services, it does not make much sense to reinvent this wheel. However, 
offering a custom tailored result presentation after completing the survey could improve 
participation rates for online surveys (usually scoring lower participation than offline / 
personally guided polls).

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

## Technologies & Tools

- PHP 7.2
- Composer ([Introduction](https://getcomposer.org/doc/00-intro.md))
- libmergepdf
- VS Code with phpfmt, PHP IntelliSense


## Survey tools

Open Source tools:
- LimeSurvey

More open tools:
- LamaPoll

Plain commercial APIs:
- [SurveyMonkey API](https://developer.surveymonkey.com/api/v3/#collectors-id-responses-id-details)
- [SurveyHero API](https://developer.surveyhero.com/api/#get-all-answers-from-response)
- [Qualtrics API](https://api.qualtrics.com/reference#getresponse-1)

None of them offering a student plan & only premium plan has API access

## LimeSurvey implementation

Approach: 
- Self-hosted installation of LimeSurvey **on the same server**.
- The survey must enforce a participant list with access tokens.
- This enforces URLs like `<SurveyUrl>/index.php?r=survey/index&sid=<SurveyId>&token=<tokenId>`.
- The token will be stored alongside with the results of the participant.
- The last page of LimeSurvey needs to implement a link to this survey-evaluator with the parameter `?token={TOKEN}`. 
- This leverages the LimeSurvey ExpressionManager syntax.
- The implementation of the `IDataCollector` uses direct access to the database to fetch the results of the participant by his token.