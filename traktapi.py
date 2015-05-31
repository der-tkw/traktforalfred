#!/usr/bin/python
# encoding: utf-8

from collections import defaultdict
from trakt import Trakt


class TraktAPI():
    authorization = defaultdict(list)
    __app_id = 1527
    __client_id = 'ef7b141679974fa49d84b0f1a2d005ba8a32e3028cdfac9f5d6a014785a7e3d0'
    __client_secret = 'd5e4f4f352ad96cac1dbf5d8b14cef03f8268fe3fb07d49811c01a9874962536'
    wf = None

    def __init__(self, wf):
        self.wf = wf

        # load authorization from settings
        if 'access_token' in self.wf.settings:
            self.authorization['access_token'] = self.wf.settings['access_token']
            self.authorization['created_at'] = self.wf.settings['created_at']
            self.authorization['expires_in'] = self.wf.settings['expires_in']
            self.authorization['refresh_token'] = self.wf.settings['refresh_token']
            self.authorization['scope'] = self.wf.settings['scope']
            self.authorization['token_type'] = self.wf.settings['token_type']
        else:
            self.authorization = {}

        # bind trakt events
        Trakt.on('oauth.token_refreshed', self.__on_token_refreshed)

        # set base url
        Trakt.base_url = 'http://api.trakt.tv'

        # set app defaults
        Trakt.configuration.defaults.app(
            id=self.__app_id
        )

        # set client defaults
        Trakt.configuration.defaults.client(
            id=self.__client_id,
            secret=self.__client_secret
        )

        # set oauth defaults
        Trakt.configuration.defaults.oauth(
            refresh=True
        )

    def __on_token_refreshed(self, response):
        self.authorization = response
        self.__storeauth()

    def __storeauth(self):
        # store access token and refresh token
        self.wf.settings['access_token'] = self.authorization['access_token']
        self.wf.settings['created_at'] = self.authorization['created_at']
        self.wf.settings['expires_in'] = self.authorization['expires_in']
        self.wf.settings['refresh_token'] = self.authorization['refresh_token']
        self.wf.settings['scope'] = self.authorization['scope']
        self.wf.settings['token_type'] = self.authorization['token_type']
        self.wf.settings.save()

    def pin(self, pin=None):
        self.authorization = Trakt['oauth'].token_exchange(pin, 'urn:ietf:wg:oauth:2.0:oob')

        if not self.authorization:
            return False
        else:
            self.__storeauth()
            return True

    def user(self):
        self.authorization['expires_in'] = 0;
        with Trakt.configuration.oauth.from_response(self.authorization):
            with Trakt.configuration.http(retry=True):
                result = Trakt['users/settings'].get()
                return result

    def checkauth(self):
        try:
            if self.user() is not None:
                return True
            else:
                return False
        except ValueError:
            return False