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