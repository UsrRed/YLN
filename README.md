# SAÉ501-502-THEOTIME-MARTEL

## README

### About this project

The SAE501-502 project aimed to create a comparator between two identities based on Wikipedia statistics.

The authors of this projcet are : 
* THÉOTIME Lukas
* MARTEL Nathan


### Key features

The main features of the project are as follows:

* Creation of new accounts (functional)
* Authentication with username and password (functional)
* Logout/Deauthentication system (functional)
* Storage of users and different comparisons with dates in the database (functional)
* Option to favorite a comparison based on the user (not available at the moment)
* Automatic creation of tables in the database (functional)
* Comparison history system based on the user (almost functional)
* Comparison of two entities based on Wikipedia statistics (non-functional)
* Use of the SlimV4 framework with its routes (functional)
* The website is responsive design (in progress)


### What script to execute to create tables in the database?

* What script to run to create tables ?
The creation of the tables in the database is automatic. To do it, we use `mysqli_multi_query`.
Therefore, as soon as a connection is made to the database, by using an .sql script, a $tables variable (which contains the commands for creating tables with an `IF NOT EXISTS`) is launched automatically to create the tables.

* Where to start ?
First, clone the project from the Git depository and move on to this project directory (sae501-502-theotime-martel).
Then, download the podman software and the podman-compose script (`dnf|apt-get|yum install podman podman-compose`).
Depending on the machines, you have to pull the original image of the two containers (`podman pull docker.io/library/mysql:latest` & `podman pull docker.io/library/php:7.4-apache`).
To finish, start the docker-compose.yaml file, which contains and specifies the configuration of our containers (`podman-compose -f docker-compose.yaml up -d`).


### How to use the application ?

Once the .yaml file is started, open a web browser and search `localhost:8080` (the PHP/Apache container provides the GUI, and you can connect locally to port 8080).
From this page, it's possible to register/login with an account by clicking on the "Login" page in the top right-hand corner of the page.
If you are registering, the message "Registration successful" should appear (same for logging in with the message "You are logged in").
Once logged in, it is possible to make a comparison between two entities by clicking to the "Comparison" page.
In addition, a time-stamped comparison history is available on the "History" page.
Once all manipulations have been completed, you can disconnect by clicking on the "Disconnect" button.


### Improvement notes ?



<!--
## Getting started

To make it easy for you to get started with GitLab, here's a list of recommended next steps.

Already a pro? Just edit this README.md and make it your own. Want to make it easy? [Use the template at the bottom](#editing-this-readme)!

## Add your files

- [ ] [Create](https://docs.gitlab.com/ee/user/project/repository/web_editor.html#create-a-file) or [upload](https://docs.gitlab.com/ee/user/project/repository/web_editor.html#upload-a-file) files
- [ ] [Add files using the command line](https://docs.gitlab.com/ee/gitlab-basics/add-file.html#add-a-file-using-the-command-line) or push an existing Git repository with the following command:

```
cd existing_repo
git remote add origin https://scm.univ-tours.fr/22107454t/sae501-502-theotime-martel.git
git branch -M main
git push -uf origin main
```

## Integrate with your tools

- [ ] [Set up project integrations](https://scm.univ-tours.fr/22107454t/sae501-502-theotime-martel/-/settings/integrations)

## Collaborate with your team

- [ ] [Invite team members and collaborators](https://docs.gitlab.com/ee/user/project/members/)
- [ ] [Create a new merge request](https://docs.gitlab.com/ee/user/project/merge_requests/creating_merge_requests.html)
- [ ] [Automatically close issues from merge requests](https://docs.gitlab.com/ee/user/project/issues/managing_issues.html#closing-issues-automatically)
- [ ] [Enable merge request approvals](https://docs.gitlab.com/ee/user/project/merge_requests/approvals/)
- [ ] [Automatically merge when pipeline succeeds](https://docs.gitlab.com/ee/user/project/merge_requests/merge_when_pipeline_succeeds.html)

## Test and Deploy

Use the built-in continuous integration in GitLab.

- [ ] [Get started with GitLab CI/CD](https://docs.gitlab.com/ee/ci/quick_start/index.html)
- [ ] [Analyze your code for known vulnerabilities with Static Application Security Testing(SAST)](https://docs.gitlab.com/ee/user/application_security/sast/)
- [ ] [Deploy to Kubernetes, Amazon EC2, or Amazon ECS using Auto Deploy](https://docs.gitlab.com/ee/topics/autodevops/requirements.html)
- [ ] [Use pull-based deployments for improved Kubernetes management](https://docs.gitlab.com/ee/user/clusters/agent/)
- [ ] [Set up protected environments](https://docs.gitlab.com/ee/ci/environments/protected_environments.html)

***

# Editing this README

When you're ready to make this README your own, just edit this file and use the handy template below (or feel free to structure it however you want - this is just a starting point!). Thank you to [makeareadme.com](https://www.makeareadme.com/) for this template.

## Suggestions for a good README
Every project is different, so consider which of these sections apply to yours. The sections used in the template are suggestions for most open source projects. Also keep in mind that while a README can be too long and detailed, too long is better than too short. If you think your README is too long, consider utilizing another form of documentation rather than cutting out information.

## Name
Choose a self-explaining name for your project.

## Description
Let people know what your project can do specifically. Provide context and add a link to any reference visitors might be unfamiliar with. A list of Features or a Background subsection can also be added here. If there are alternatives to your project, this is a good place to list differentiating factors.

## Badges
On some READMEs, you may see small images that convey metadata, such as whether or not all the tests are passing for the project. You can use Shields to add some to your README. Many services also have instructions for adding a badge.

## Visuals
Depending on what you are making, it can be a good idea to include screenshots or even a video (you'll frequently see GIFs rather than actual videos). Tools like ttygif can help, but check out Asciinema for a more sophisticated method.

## Installation
Within a particular ecosystem, there may be a common way of installing things, such as using Yarn, NuGet, or Homebrew. However, consider the possibility that whoever is reading your README is a novice and would like more guidance. Listing specific steps helps remove ambiguity and gets people to using your project as quickly as possible. If it only runs in a specific context like a particular programming language version or operating system or has dependencies that have to be installed manually, also add a Requirements subsection.

## Usage
Use examples liberally, and show the expected output if you can. It's helpful to have inline the smallest example of usage that you can demonstrate, while providing links to more sophisticated examples if they are too long to reasonably include in the README.

## Support
Tell people where they can go to for help. It can be any combination of an issue tracker, a chat room, an email address, etc.

## Roadmap
If you have ideas for releases in the future, it is a good idea to list them in the README.

## Contributing
State if you are open to contributions and what your requirements are for accepting them.

For people who want to make changes to your project, it's helpful to have some documentation on how to get started. Perhaps there is a script that they should run or some environment variables that they need to set. Make these steps explicit. These instructions could also be useful to your future self.

You can also document commands to lint the code or run tests. These steps help to ensure high code quality and reduce the likelihood that the changes inadvertently break something. Having instructions for running tests is especially helpful if it requires external setup, such as starting a Selenium server for testing in a browser.

## Authors and acknowledgment
Show your appreciation to those who have contributed to the project.

## License
For open source projects, say how it is licensed.

## Project status
If you have run out of energy or time for your project, put a note at the top of the README saying that development has slowed down or stopped completely. Someone may choose to fork your project or volunteer to step in as a maintainer or owner, allowing your project to keep going. You can also make an explicit request for maintainers.
-->
