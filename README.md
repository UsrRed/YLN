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
* Storage of users and different comparisons with dates in the database (functional) (functional).
* Option to favorite a comparison based on the user (functional)
* Automatically create tables in the database (functional).
* Log in and save data in the database (functional).
* User-based comparison history (functional).
* Compare two entiites using Wikipedia statistics (almost functionnal).
* Use the SlimV4 framework with its routes (functional)
* The website is responsive design (functional)
* Pagination of History and Favorites pages (functional)
* The website is available in two languages: French and English (non-functional).
* Website security: Prevention of SQL injections (non-functional)
* Encryption of database information (non-functional).
* Website security: Implementation of HTTPS to encrypt exchanged data (functional).
* Website security: Security and redondancy mechanism (Load Balancing) to ensure continuous availability (functional).
* Fault tolerance, one maximum fault (functional)
* Adding a superuser with privileged access. This superuser can access all the site's features, including viewing the whole history, all favorites, all users, etc. (non-functional)
* Settings page where the user can add information to their profile, change/update their password, delete their account, and submit a question to the user support: an email is sent to the site administrators. (functiona)
* The application administrator can access a private page containing all user questions (support inquiries), and he is the only one who have access to this page. (functional)

### What script to execute to create tables in the database?

* What script to run to create tables ?

The creation of the tables in the database is automatic. To do it, we use `mysqli_multi_query`.
Therefore, as soon as a connection is made to the database, by using an .sql script, a $tables variable (which contains the commands for creating tables with an `IF NOT EXISTS`) is launched automatically to create the tables.

* Where to start ?

First, clne the project from the Git repository and navigate to the project directory (`cd sae501-502-theotime-martel`). Next, download the Podman software and the Podman-compose script using the appropriate package manager (`dnf|apt-get|yum install podman podman-compose`).

Depending on your machine, you may need to pull the original image for the three containers (`podman pull docker.io/library/mysql:latest` & `podman pull docker.io/library/php:8.2-fpm` & `podman pull docker.io/library/nginx:alpine` & `podman pull docker.io/library/haproxy:alpine`). To do this, run the "ScriptImage" script, and the images should be imported or you can do it manually.

Afterward, launch the "docker-compose.yaml" file, which contains and specifies the configuration of our containers using the following command : `podman-compose -f docker-compose.yaml up -d`
<!--
Once the "docker-compose" file is running, execute the "IpMonSite.sh" script (`bash IpMonSite.sh|./IpMonSite.sh`). This will provide you with the IP address of the application, with or without load balancing, according to your preferrence.-->

### How to use the application ?

Once the .yaml file is started, open a web browser and enter `https://[IP_Address_Provided_By_The_IpMonSite.sh_script]` (the PHP and Nginx container provides the user interface). You can also connect using HTTP: `http://[IP_Address_Provided_By_The_IpMonSite.sh_script]`. The IP address is the Haproxy container (very rarely different from 172.18.0.4) which will distribute the load between two nginx WEB containers.

From this page, it is possible to register/login with an account by clicking on the "Login" page in the top right-hand corner of the page. If you register, you will need to log in with the same login credentials.

Once logged in, it is possible to compare two entities by clicking on the "Comparison" page and entering the title of a Wikipedia page, such as "Paris_Saint-Germain_Football_Club" and "Olympique_de_Marseille". You can also add your favorite comparisons by clicking the "Add to Favorites" button and view your favorites on the Favorites page.

Additionally, a timestamped comparison history is available on the "History" page. Once all operations are completed, you can log out by clicking the "Logout" button. It is not possible to compare two identities or view the history and favorites without logging in.

### Improvement notes ?

There is a small issue in the website design. To access the Admin FAQ page (which is only accessible by the application administrator), there needs to be an administrator's entry in the FAQ table first. In other words, if the administrator has not posted questions on the FAQ page (/trait_faq), no one can access on this page.

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
