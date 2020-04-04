# LimeSurvey on Nginx and MySql

## Nginx with php support

```bash
sudo apt update
sudo apt install nginx
sudo apt install php7.2
sudo apt install php7.2-fpm
```

Enable the php support for nginx by uncommenting the corresponding part in the config 
(and adjusting version numbers if necessary)

```bash 
sudo nano /etc/nginx/sites-available/default
sudo systemctl restart nginx
``` 

## Extensions for php

```bash 
# Standard extensions 
sudo apt install php7.2-mbstring php7.2-pdo-mysql php7.2-gettext
sudo phpenmod mbstring
# Optional extensions
sudo apt install php7.2-zip php7.2-gd php7.2-ldap php7.2-imap php-sodium
``` 

## MySql

```bash
sudo apt install mysql-server
# For production environment:
sudo mysql_secure_installation
# As a GUI
sudo apt install emma
``` 

# Insights on the inner workings of LimeSurvey's database

If using the API for accessing survey data does not work, direct access to LimeSurvey's database could be an alternative approach. This of course only works for a self-hosted installation of LimeSurvey on the same host where the Application runs on.

Insights:
- Question codes and infos are stored in tables following the convention `$prefix."_questions"`
- The default naming pattern for questions in their `title` property is:
  - `G<SubgroupNum:00>Q<QuestionNum:00>` for main questions
  - `SQ<SubquestionNum:00>` for subquestions -> Subquestion number could be 'question_order' number?
  - `<SID>X<GroupId:no leading 0>X<SubgroupNum:no leading 0><if Subquestion:title>` is the question's column in the result table
  - This table also contains the `qid`
- Get question text from `$prefix."_question_".???` by `qid` <- ??? is some random id
- Group names are in `$prefix."_group_".???` by `<groupId: no leading 0>` <- ??? is some random id 
- Actual responses are in `$prefix."_survey_".$surveyId` where submitdate is not empty