#!/usr/bin/python
# encoding: utf-8

import sys
import logging
from traktapi import TraktAPI
from workflow import Workflow


def main(wf):
    logging.basicConfig(level=logging.DEBUG)

    action = wf.args[0]
    if len(wf.args) > 1:
        params = wf.args[1:]

    traktapi = TraktAPI(wf)

    """
    Actions WITHOUT autentication
    """
    if action == 'auth':
        if traktapi.pin(params[0]):
            print 'Successfully authenticated.'
        else:
            print 'Invalid PIN. Please try again.'
        return

    """
    Actions WITH authentication
    """
    # check authentication
    if not traktapi.checkauth():
        wf.add_item(u'Not Authenticated', u'Please use the keyword trakt-auth and provide a valid PIN.')
        wf.send_feedback()
        return

    if action == 'user':
        user = traktapi.user()
        wf.add_item(user['user']['username'], u'Username')
        wf.add_item(user['user']['name'], u'Name')

    wf.send_feedback()


if __name__ == '__main__':
    wf = Workflow()
    sys.exit(wf.run(main))
