Matrix plugin for Kanboard
==============================

[![Build Status](https://travis-ci.org/kanboard/plugin-matrix.svg?branch=master)](https://travis-ci.org/kanboard/plugin-matrix)

Receive Kanboard notifications on Matrix.

Authors
-------

- Frederic Guillot wrote the original Mattermost plugin
- Andrej Shadura adapted it to work with the Matrix API
- License: MIT

Requirements
------------

- Kanboard >= 1.0.37
- An account at a Matrix homeserver

Installation
------------

You have the choice between 3 methods:

1. Install the plugin from the Kanboard plugin manager in one click
2. Download the zip file and decompress everything under the directory `plugins/Matrix`
3. Clone this repository into the folder `plugins/Matrix`

Note: Plugin folder is case-sensitive.

Configuration
-------------

### Define Matrix homeserver configuration

- In **Application settings > Integrations > Matrix**, set the homeserver URL, the username and the
password. Alternatively, if you have an access token, you can specify it instead of the username and
the password.

### Receive project notifications to a room

- Go to the project settings then choose **Integrations > Matrix**
- Set the chatroom address, for example: **#room:matrix.org**
- Enable Matrix in your project notifications **Notifications > Matrix**

## Troubleshooting

- Enable the debug mode
- All connection errors with the Matrix API are recorded in the log files `data/debug.log`
