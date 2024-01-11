# Changelog for the SAE501-502 Application

## Version 3.2 (11/01/2024 - latest)

### New features and improvements

- Master/slave database replication
- Addition of an environment file (.env).
- Addition of Netdata container.
- Addition of a dark theme
- Addition of a crontab in the syslog container
- Uninstall script

## Version 3.1 (02/12/2023)

### New features and improvements

- Addition of Portainer container.
- Mandatory strong password requirement for application users.
- Internal logs for activity within the application + pie chart for the activity
- Addition of Promtail, Loki, Grafana containers for a graphical view of the application logs

## Version 3.0 (25/11/2023)

### New Features and Enhancements

- Implemented syslog for centralized logging to enhance system monitoring.
- Set up an "IDS/IPS" (Intrusion Detection System/Intrusion Prevention System) for improved security.
- Added user banning functionality to enhance security measures.

## Version 2.2 (16/11/2023)

### Bug Fixes - API REST 

- Transformation of the comparison result into json format with value input using GET method.
- Addressed various bugs identified in previous versions
- Enhanced the rendering of comparisons for a more polished and user-friendly experience.

## Version 2.1 (13/11/2023)

### Major Improvements

- Significantly improved the appearance of comparisons for a cleaner user experience.
- Enhanced input sanitization for better security of user data.
- Implementation of password hashing and salting in the databse
- Addition of PHP/Bootstrap alerts notifications for users.

## Version 2.0 (11/11/2023)

### New Features & performance and security enhancements

- Added the "Forgot Password" feature: a random password is sent to the user via email.
- Users can now delte their accounts.
- Pagination for History and Favorites pages, and the users can download comparisons in CSV format.
- Introduced Nginx container redundancy via Haproxy for increased availability.
- Configured HTTPs support to have a better communication security.
- Improved rendering of comparisons using the MediaWiki API
- Added support functionality through email communication
- Users can now change their passwords.

## Version 1.3 (08/11/2023)

### New Features

- Integration of comparaison functionality using the MediaWiki API
- Added an information button to indicate the user's login/logout status.

## Version 1.2 (31/10/2023)

### New Features

- Implemented a user comparison history
- Added a favorites page for personalized comparison management

## Version 1 (29/10/2023)

### Initial Features

- Operational architecture of the application is now in place.
- Registration and login on the site are possible with a database
- Implemented SLIM V4 routes
