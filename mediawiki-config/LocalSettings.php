<?php
# This file was automatically generated by the MediaWiki 1.30.0
# installer. If you make manual changes, please keep track in case you
# need to recreate them later.
#
# See includes/DefaultSettings.php for all configurable settings
# and their default values, but don't forget to make changes in _this_
# file, not there.
#
# Further documentation for configuration settings may be found at:
# https://www.mediawiki.org/wiki/Manual:Configuration_settings

# Protect against web entry
if ( !defined( 'MEDIAWIKI' ) ) {
	exit;
}

## Uncomment this to disable output compression
# $wgDisableOutputCompression = true;

$wgSitename = "Lingua Libre";
$wgMetaNamespace = "LinguaLibre";

# Private settings such as passwords, that shouldn't be published
# Needs to be before db.php
require __DIR__ . "/private/PrivateSettings.php";

## The URL base path to the directory containing the wiki;
## defaults for all runtime URL paths are based off of this.
## For more information on customizing the URLs
## (like /w/index.php/Page_title to /wiki/Page_title) please see:
## https://www.mediawiki.org/wiki/Manual:Short_URL
$wgScriptPath = "";
$wgScriptExtension = ".php";
$wgArticlePath = "/wiki/$1";
$wgUsePathInfo = true;

if($_SERVER['SERVER_NAME'] == 'lingualibre.fr') {
    $isBeta = False;
    $wgServer = "https://lingualibre.fr";
}
else {
    $isBeta = True;
    $wgServer = "https://" . $_SERVER['SERVER_NAME'];
}

## The URL path to static resources (images, scripts, etc.)
$wgResourceBasePath = $wgScriptPath;

## The URL path to the logo.  Make sure you change this from the default,
## or else you'll overwrite your logo when you upgrade!
$wgLogo = "$wgResourceBasePath/resources/assets/ll.png";
$wgFavicon = "$wgResourceBasePath/resources/assets/favicon.ico";

## UPO means: this is also a user preference option

$wgEnableEmail = false;
$wgEnableUserEmail = true; # UPO

$wgEmergencyContact = "contact@lingualibre.fr";
$wgPasswordSender = "contact@lingualibre.fr";

$wgEnotifUserTalk = false; # UPO
$wgEnotifWatchlist = false; # UPO
$wgEmailAuthentication = true;

## Database settings
$wgDBtype = "mysql";
$wgDBserver = "localhost";

# MySQL specific settings
$wgDBprefix = "";

# MySQL table options to use during installation or update
$wgDBTableOptions = "ENGINE=InnoDB, DEFAULT CHARSET=binary";

# Experimental charset support for MySQL 5.0.
$wgDBmysql5 = false;

## Shared memory settings
$wgMainCacheType = CACHE_ACCEL;
$wgSessionCacheType = CACHE_DB;
$wgMemCachedServers = [];
$wgObjectCacheSessionExpiry = 864000;

## To enable image uploads, make sure the 'images' directory
## is writable, then set this to true:
$wgEnableUploads = true;
$wgFileExtensions[] = 'wav';
$wgFileExtensions[] = 'ogg';
$wgFileExtensions[] = 'flac';
$wgFileExtensions[] = 'mp3';
#$wgUseImageMagick = true;
#$wgImageMagickConvertCommand = "/usr/bin/convert";

# InstantCommons allows wiki to use images from https://commons.wikimedia.org
$wgUseInstantCommons = true;

# Periodically send a pingback to https://www.mediawiki.org/ with basic data
# about this MediaWiki instance. The Wikimedia Foundation shares this data
# with MediaWiki developers to help guide future development efforts.
$wgPingback = true;

## If you use ImageMagick (or any other shell command) on a
## Linux server, this will need to be set to the name of an
## available UTF-8 locale
$wgShellLocale = "C.UTF-8";

## Set $wgCacheDirectory to a writable directory on the web server
## to make your wiki go slightly faster. The directory should not
## be publically accessible from the web.
#$wgCacheDirectory = "$IP/cache";

# Site language code, should be one of the list in ./languages/data/Names.php
$wgLanguageCode = "en";

# Changing this will log out all existing sessions.
$wgAuthenticationTokenVersion = "1";

## For attaching licensing metadata to pages, and displaying an
## appropriate copyright notice / icon. GNU Free Documentation
## License and Creative Commons licenses are supported so far.
$wgRightsPage = ""; # Set to the title of a wiki page that describes your license/copyright
$wgRightsUrl = "https://creativecommons.org/licenses/by-sa/4.0/";
$wgRightsText = "Creative Commons attribution partage à l'identique";
$wgRightsIcon = "$wgResourceBasePath/resources/assets/licenses/cc-by-sa.png";

# Path to the GNU diff3 utility. Used for conflict resolution.
$wgDiff3 = "/usr/bin/diff3";

# Disable annon editing
$wgGroupPermissions['*']['edit'] = false;

## Default skin: you can change the default skin. Use the internal symbolic
## names, ie 'vector', 'monobook':
$wgDefaultSkin = "foreground";

# Enabled skins.
# The following skins were automatically enabled:
wfLoadSkin( 'Vector' );
wfLoadSkin( 'foreground' );

$wgForegroundFeatures = array(
  'navbarIcon' => true,
  'showActionsForAnon' => true,
  'NavWrapperType' => 'divonly',
  'showHelpUnderTools' => false,
  'showRecentChangesUnderTools' => true,
  'IeEdgeCode' => 1,
  'showFooterIcons' => true,
);

# Enabled extensions.
# The following extensions were automatically enabled:
wfLoadExtension( 'Cite' );
wfLoadExtension( 'Gadgets' );
wfLoadExtension( 'InputBox' );
wfLoadExtension( 'Interwiki' );
wfLoadExtension( 'Nuke' );
wfLoadExtension( 'ParserFunctions' );
wfLoadExtension( 'PdfHandler' );
wfLoadExtension( 'Poem' );
wfLoadExtension( 'SpamBlacklist' );
wfLoadExtension( 'SyntaxHighlight_GeSHi' );
wfLoadExtension( 'TitleBlacklist' );
wfLoadExtension( 'WikiEditor' );
wfLoadExtension( 'CodeEditor' );
wfLoadExtension( 'Scribunto' );

#Configure scribunto
$wgScribuntoDefaultEngine = 'luastandalone';

# Edit toolbars configurations
$wgHiddenPrefs[] = 'usebetatoolbar';
$wgDefaultUserOptions['usebetatoolbar'] = 1;
$wgScribuntoUseCodeEditor = true;
$wgScribuntoUseGeSHi = true;

# Remove the default TemporaryPassword and LocalPassword authentication provider
# to let OAuth as the only authentication provider usable.
$wgAuthManagerAutoConfig['primaryauth'] = [];

# Activate the OAuthAuthentication extension
wfLoadExtension( 'OAuthAuthentication' );

if($isBeta) {
    $wgOAuthAuthenticationUrl = 'https://oauth.0x010c.fr/index.php?title=Special:OAuth';
    $wgOAuthAuthenticationCanonicalUrl = 'https://oauth.0x010c.fr';
    $wgOAuthAuthenticationRemoteName = 'OAuthWiki';
} else {
    $wgOAuthAuthenticationUrl = 'https://commons.wikimedia.org/w/index.php?title=Special:OAuth';
    $wgOAuthAuthenticationCanonicalUrl = 'https://commons.wikimedia.org';
    $wgOAuthAuthenticationRemoteName = 'Wikimedia Commons';
}
$wgOAuthAuthenticationAllowLocalUsers = false;
$wgOAuthAuthenticationAccountUsurpation = true;
$wgOAuthAuthenticationReplaceLoginLink = true;
$wgOAuthAuthenticationValidateSSL = true;
$wgOAuthAuthenticationCallbackUrl = 'https://lingualibre.fr/wiki/Special:OAuthLogin/finish';

# Activate the Wikibase Repository extension
$wgEnableWikibaseRepo = true;
$wgEnableWikibaseClient = true;
require_once "$IP/extensions/Wikibase/repo/Wikibase.php";
require_once "$IP/extensions/Wikibase/repo/ExampleSettings.php";
require_once "$IP/extensions/Wikibase/client/WikibaseClient.php";
require_once "$IP/extensions/Wikibase/client/ExampleSettings.php";

$wgWBRepoSettings['entityNamespaces']['item'] = NS_MAIN;
$wgWBRepoSettings['entityNamespaces']['property'] = WB_NS_PROPERTY;

$wgWBRepoSettings['siteLinkGroups'] = array(); #We don't need sitelinks

$wgWBRepoSettings['formatterUrlProperty'] = 'P10'; # see https://github.com/wikimedia/mediawiki-extensions-Wikibase/blob/master/docs/options.wiki
$wgWBRepoSettings['canonicalUriProperty'] = 'P22';

#$wgWBRepoSettings['sparqlEndpoint'] = 'URL to the service description of the SPARQL end point for the repository'


# Activate the Upload2Commons extension
wfLoadExtension( 'Upload2Commons' );
if($isBeta) {
    $wgUpload2CommonsApiUrl = 'https://oauth.0x010c.fr/api.php';
} else {
    $wgUpload2CommonsApiUrl = 'https://commons.wikimedia.org/w/api.php';
}

# Create the "list" namespace
define('NS_LIST', 142);
define('NS_LIST_TALK', 143);
$wgExtraNamespaces[NS_LIST] = 'List';
$wgExtraNamespaces[NS_LIST_TALK] = 'List_talk';
$wgNamespacesWithSubpages[NS_LIST] = true;

# Add a "DataViz" namespace alias, to manage old links in the wild to this removed namespace
$wgNamespaceAliases['DataViz'] = NS_PROJECT;

# Activate the RecordingExtension
wfLoadExtension( 'RecordWizard' );
$wgRecordWizardConfig['properties']['langCode'] = 'P17';
$wgRecordWizardConfig['properties']['iso3'] = 'P13';
$wgRecordWizardConfig['properties']['gender'] = 'P8';
$wgRecordWizardConfig['properties']['spokenLanguages'] = 'P4';
$wgRecordWizardConfig['properties']['instanceOf'] = 'P2';
$wgRecordWizardConfig['properties']['linkedUser'] = 'P11';
$wgRecordWizardConfig['properties']['subclassOf'] = 'P9';
$wgRecordWizardConfig['properties']['audioRecord'] = 'P3';
$wgRecordWizardConfig['properties']['locutor'] = 'P5';
$wgRecordWizardConfig['properties']['date'] = 'P6';
$wgRecordWizardConfig['properties']['transcription'] = 'P7';
$wgRecordWizardConfig['properties']['wikidataId'] = 'P12';
$wgRecordWizardConfig['properties']['languageLevel'] = 'P16';
$wgRecordWizardConfig['properties']['residencePlace'] = 'P14';
$wgRecordWizardConfig['properties']['learningPlace'] = 'P15';
$wgRecordWizardConfig['properties']['qualifier'] = 'P18';
$wgRecordWizardConfig['properties']['wikipediaTitle'] = 'P19';
$wgRecordWizardConfig['properties']['wiktionaryEntry'] = 'P20';

$wgRecordWizardConfig['items']['genderMale'] = 'Q16';
$wgRecordWizardConfig['items']['genderFemale'] = 'Q17';
$wgRecordWizardConfig['items']['genderOther'] = 'Q18';
$wgRecordWizardConfig['items']['language'] = 'Q4';
$wgRecordWizardConfig['items']['locutor'] = 'Q3';
$wgRecordWizardConfig['items']['record'] = 'Q2';
$wgRecordWizardConfig['items']['word'] = 'Q8';
$wgRecordWizardConfig['items']['langLevelNative'] = 'Q15';
$wgRecordWizardConfig['items']['langLevelGood'] = 'Q14';
$wgRecordWizardConfig['items']['langLevelAverage'] = 'Q13';
$wgRecordWizardConfig['items']['langLevelBeginner'] = 'Q12';

$wgRecordWizardConfig['listNamespace'] = NS_LIST;

# Activate QueryViz extension
wfLoadExtension( 'QueryViz' );
$wgQueryVizEndpoint = "https://lingualibre.fr/bigdata/namespace/wdq/sparql";

# Activate i18n-related extensions
wfLoadExtension( 'Babel' );

wfLoadExtension( 'cldr' );

wfLoadExtension( 'CleanChanges' );
$wgCCTrailerFilter = true;
$wgCCUserFilter = false;
$wgDefaultUserOptions['usenewrc'] = 1;

wfLoadExtension( 'LocalisationUpdate' );
$wgLocalisationUpdateDirectory = "$IP/cache";

require_once "$IP/extensions/Translate/Translate.php";
$wgGroupPermissions['user']['translate'] = true;
$wgGroupPermissions['user']['translate-messagereview'] = true;
$wgGroupPermissions['user']['translate-groupreview'] = true;
$wgGroupPermissions['user']['translate-import'] = true;
$wgGroupPermissions['sysop']['pagetranslation'] = true;
$wgGroupPermissions['sysop']['translate-manage'] = true;
$wgTranslateDocumentationLanguageCode = 'qqq';
$wgExtraLanguageNames['qqq'] = 'Message documentation';

wfLoadExtension( 'UniversalLanguageSelector' );


$wgForceUIMsgAsContentMsg = array( 'licenses' );

# Activate a media player extension
wfLoadExtension( 'TimedMediaHandler' );

$wgEnableTranscode = true;
// The total amout of time a transcoding shell command can take:
$wgTranscodeBackgroundTimeLimit = 3600 * 8;
// Maximum amount of virtual memory available to transcoding processes in KB
$wgTranscodeBackgroundMemoryLimit = 2 * 1024 * 1024; // 2GB avconv, ffmpeg2theora mmap resources so virtual memory needs to be high enough
// Maximum file size transcoding processes can create, in KB
$wgTranscodeBackgroundSizeLimit = 3 * 1024 * 1024; // 3GB

// Number of threads to use in avconv for transcoding
$wgFFmpegThreads = 1;

// The NS for TimedText (registered on MediaWiki.org)
$wgTimedTextNS = 710;

$wgEnabledAudioTranscodeSet = [
    'vorbis',
    'mp3',
];

// If mp3 source assets can be ingested:
$wgTmhEnableMp3Uploads = true;


# Activate logging
$wgDebugLogFile = '/var/log/mediawiki/' . $wgDBname . '.log';


# Allow the magic word {{DISPLAYTITLE}}
$wgAllowDisplayTitle = true;
$wgRestrictDisplayTitle = false;

# Add some languages not supported by default in MediaWiki
$wgExtraLanguageNames['kea'] = 'Kabuverdianu';
$wgExtraLanguageNames['nod'] = 'ᨣᩴᩤᨾᩮᩥᨦ';
$wgExtraLanguageNames['nys'] = 'Noongar';
$wgExtraLanguageNames['ota'] = 'ﻞﺳﺎﻧ ﺕﻭﺮﻛﻯ';
$wgExtraLanguageNames['rwr'] = 'मारवाड़ी';
$wgExtraLanguageNames['sje'] = 'bidumsámegiella';
$wgExtraLanguageNames['smj'] = 'julevsámegiella';
$wgExtraLanguageNames['srq'] = 'mbia cheë';
$wgExtraLanguageNames['tokipona'] = 'Toki Pona';
$wgExtraLanguageNames['shy-latn'] = 'Tacawit';

# Raise limit for heavy jobs
$wgMemoryLimit = '128M';


# Activate the debug toolbar; do not leave it in production!
if($isBeta) {
    $wgDebugToolbar = true;
    error_reporting( -1 );
    ini_set( 'display_errors', 1 );
}
ini_set( 'display_errors', 1 );
error_reporting( -1 );
