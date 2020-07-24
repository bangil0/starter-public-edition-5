<?php namespace Common\Config;

use CodeIgniter\Config\BaseConfig;

class AssetsCompile extends BaseConfig
{
    public $tasks;

    public function __construct() {

        // An autoprefixer option: Supported browsers.

        $this->autoprefixer_browsers = [
            '>= 0.1%',
            'last 2 versions',
            'Firefox ESR',
            'Safari >= 7',
            'iOS >= 7',
            'ie >= 10',
            'Edge >= 12',
            'Android >= 4',
        ];

        // The following command-line runs all the tasks:
        // php spark assets:compile

        $this->tasks = [

            // Compiling visual themes with Semantic/Fomantic UI might require a lot
            // of memory for node.js. In such case try from a command line this:
            // export NODE_OPTIONS=--max-old-space-size=8192

            // php spark assets:compile front_default_css
            [
                'name' => 'front_default_css',
                'type' => 'merge_css',
                'destination' => DEFAULTFCPATH.'themes/front_default/css/front.min.css',
                'sources' => [
                    [
                        'source' => DEFAULTFCPATH.'themes/front_default/src/front.less',
                        'type' => 'less',
                        'less' => [],
                        'autoprefixer' => ['browsers' => $this->autoprefixer_browsers],
                        'cssmin' => [],
                    ],
                ],
                'before' => [$this, 'prepare_semantic_source'],
                'after' => [
                    [$this, 'create_sha384'],
                    [$this, 'create_sha384_base64'],
                ],
            ],

        ];
    }

    public function create_sha384($task) {

        $destination_hash = $task['destination'].'.sha384';
        $hash = hash('sha384', $task['result']);
        write_file($destination_hash, $hash);
        @chmod($destination_hash, FILE_WRITE_MODE);
    }

    public function create_sha384_base64($task) {

        $destination_hash = $task['destination'].'.sha384.base64';
        $hash = base64_encode(hash('sha384', $task['result']));
        write_file($destination_hash, $hash);
        @chmod($destination_hash, FILE_WRITE_MODE);
    }

    public function prepare_semantic_source($task) {

        $semantic_file = DEFAULTFCPATH.'assets/composer-asset/fomantic/ui/src/semantic.less';

        file_put_contents($semantic_file, <<<'EOT'
@theme-loader-path: ''; // Added by Ivan Tcholakov, 05-JUL-2020.

/*

███████╗███████╗███╗   ███╗ █████╗ ███╗   ██╗████████╗██╗ ██████╗    ██╗   ██╗██╗
██╔════╝██╔════╝████╗ ████║██╔══██╗████╗  ██║╚══██╔══╝██║██╔════╝    ██║   ██║██║
███████╗█████╗  ██╔████╔██║███████║██╔██╗ ██║   ██║   ██║██║         ██║   ██║██║
╚════██║██╔══╝  ██║╚██╔╝██║██╔══██║██║╚██╗██║   ██║   ██║██║         ██║   ██║██║
███████║███████╗██║ ╚═╝ ██║██║  ██║██║ ╚████║   ██║   ██║╚██████╗    ╚██████╔╝██║
╚══════╝╚══════╝╚═╝     ╚═╝╚═╝  ╚═╝╚═╝  ╚═══╝   ╚═╝   ╚═╝ ╚═════╝     ╚═════╝ ╚═╝

  Import this file into your LESS project to use Fomantic-UI without build tools
*/

/* Global */
& { @import "definitions/globals/reset"; }
& { @import "definitions/globals/site"; }

/* Elements */
& { @import "definitions/elements/button"; }
& { @import "definitions/elements/container"; }
& { @import "definitions/elements/divider"; }
& { @import "definitions/elements/emoji"; }
& { @import "definitions/elements/flag"; }
& { @import "definitions/elements/header"; }
& { @import "definitions/elements/icon"; }
& { @import "definitions/elements/image"; }
& { @import "definitions/elements/input"; }
& { @import "definitions/elements/label"; }
& { @import "definitions/elements/list"; }
& { @import "definitions/elements/loader"; }
& { @import "definitions/elements/placeholder"; }
& { @import "definitions/elements/rail"; }
& { @import "definitions/elements/reveal"; }
& { @import "definitions/elements/segment"; }
& { @import "definitions/elements/step"; }
& { @import "definitions/elements/text"; }

/* Collections */
& { @import "definitions/collections/breadcrumb"; }
& { @import "definitions/collections/form"; }
& { @import "definitions/collections/grid"; }
& { @import "definitions/collections/menu"; }
& { @import "definitions/collections/message"; }
& { @import "definitions/collections/table"; }

/* Views */
& { @import "definitions/views/ad"; }
& { @import "definitions/views/card"; }
& { @import "definitions/views/comment"; }
& { @import "definitions/views/feed"; }
& { @import "definitions/views/item"; }
& { @import "definitions/views/statistic"; }

/* Modules */
& { @import "definitions/modules/accordion"; }
& { @import "definitions/modules/calendar"; }
& { @import "definitions/modules/checkbox"; }
& { @import "definitions/modules/dimmer"; }
& { @import "definitions/modules/dropdown"; }
& { @import "definitions/modules/embed"; }
& { @import "definitions/modules/modal"; }
& { @import "definitions/modules/nag"; }
& { @import "definitions/modules/popup"; }
& { @import "definitions/modules/progress"; }
& { @import "definitions/modules/slider"; }
& { @import "definitions/modules/rating"; }
& { @import "definitions/modules/search"; }
& { @import "definitions/modules/shape"; }
& { @import "definitions/modules/sidebar"; }
& { @import "definitions/modules/sticky"; }
& { @import "definitions/modules/tab"; }
& { @import "definitions/modules/toast"; }
& { @import "definitions/modules/transition"; }
EOT
        );

        @chmod($semantic_file, FILE_WRITE_MODE);

//------------------------------------------------------------------------------

        $semantic_theme_config_file = DEFAULTFCPATH.'assets/composer-asset/fomantic/ui/src/theme.config';

        file_put_contents($semantic_theme_config_file, <<<'EOT'
/*

████████╗██╗  ██╗███████╗███╗   ███╗███████╗███████╗
╚══██╔══╝██║  ██║██╔════╝████╗ ████║██╔════╝██╔════╝
   ██║   ███████║█████╗  ██╔████╔██║█████╗  ███████╗
   ██║   ██╔══██║██╔══╝  ██║╚██╔╝██║██╔══╝  ╚════██║
   ██║   ██║  ██║███████╗██║ ╚═╝ ██║███████╗███████║
   ╚═╝   ╚═╝  ╚═╝╚══════╝╚═╝     ╚═╝╚══════╝╚══════╝

*/

/*******************************
        Theme Selection
*******************************/

/* To override a theme for an individual element
   specify theme name below
*/

/* Global */
@site       : 'default';
@reset      : 'default';

/* Elements */
@button     : 'default';
@container  : 'default';
@divider    : 'default';
@emoji      : 'default';
@flag       : 'default';
@header     : 'default';
@icon       : 'default';
@image      : 'default';
@input      : 'default';
@label      : 'default';
@list       : 'default';
@loader     : 'default';
@placeholder: 'default';
@rail       : 'default';
@reveal     : 'default';
@segment    : 'default';
@step       : 'default';
@text       : 'default';

/* Collections */
@breadcrumb : 'default';
@form       : 'default';
@grid       : 'default';
@menu       : 'default';
@message    : 'default';
@table      : 'default';

/* Modules */
@accordion  : 'default';
@calendar   : 'default';
@checkbox   : 'default';
@dimmer     : 'default';
@dropdown   : 'default';
@embed      : 'default';
@modal      : 'default';
@nag        : 'default';
@popup      : 'default';
@progress   : 'default';
@slider     : 'default';
@rating     : 'default';
@search     : 'default';
@shape      : 'default';
@sidebar    : 'default';
@sticky     : 'default';
@tab        : 'default';
@toast      : 'default';
@transition : 'default';

/* Views */
@ad         : 'default';
@card       : 'default';
@comment    : 'default';
@feed       : 'default';
@item       : 'default';
@statistic  : 'default';

/*******************************
            Folders
*******************************/

/* Path to theme packages */
@themesFolder : 'themes';

/* Path to site override folder */
@siteFolder  : 'site';


/*******************************
         Import Theme
*******************************/

// Modified by Ivan Tcholakov, 05-JUL-2020.
//@import (multiple) "theme.less";
@import (multiple) "@{theme-loader-path}theme.less";
//

/* End Config */
EOT
        );

        @chmod($semantic_theme_config_file, FILE_WRITE_MODE);

//------------------------------------------------------------------------------

        $semantic_icon_definition_file = DEFAULTFCPATH.'assets/composer-asset/fomantic/ui/src/definitions/elements/icon.less';

        file_put_contents($semantic_icon_definition_file, <<<'EOT'
/*!
 * # Fomantic-UI - Icon
 * http://github.com/fomantic/Fomantic-UI/
 *
 *
 * Released under the MIT license
 * http://opensource.org/licenses/MIT
 *
 */


/*******************************
            Theme
*******************************/

@type    : 'element';
@element : 'icon';

@import (multiple) '../../theme.config';


/*******************************
             Icon
*******************************/

// Removed by Ivan Tcholakov, 10-JUL-2020.
// See https://github.com/fomantic/Fomantic-UI/issues/1560
/*
@font-face {
  font-family: 'Icons';
  src: @fallbackSRC;
  src: @src;
  font-style: normal;
  font-weight: @normal;
  font-variant: normal;
  text-decoration: inherit;
  text-transform: none;
}
*/

i.icon {
  display: inline-block;
  opacity: @opacity;

  margin: 0 @distanceFromText 0 0;

  width: @width;
  height: @height;

  font-family: 'Icons';
  font-style: normal;
  font-weight: @normal;
  text-decoration: inherit;
  text-align: center;

  speak: none;
  -moz-osx-font-smoothing: grayscale;
  -webkit-font-smoothing: antialiased;
  backface-visibility: hidden;
}

i.icon:before {
  background: none !important;
}

/*******************************
             Types
*******************************/

& when (@variationIconLoading) {
  /*--------------
      Loading
  ---------------*/

  i.icon.loading {
    height: 1em;
    line-height: 1;
    animation: loader @loadingDuration linear infinite;
  }
}

/*******************************
             States
*******************************/

i.icon:hover, i.icons:hover,
i.icon:active, i.icons:active,
i.emphasized.icon:not(.disabled), i.emphasized.icons:not(.disabled) {
  opacity: 1;
}

& when (@variationIconDisabled) {
  i.disabled.icon, i.disabled.icons {
    opacity: @disabledOpacity;
    cursor: default;
    pointer-events: none;
  }
}

/*******************************
           Variations
*******************************/

& when (@variationIconFitted) {
  /*-------------------
          Fitted
  --------------------*/

  i.fitted.icon {
    width: auto;
    margin: 0 !important;
  }
}

& when (@variationIconLink) {
  /*-------------------
           Link
  --------------------*/

  i.link.icon:not(.disabled), i.link.icons:not(.disabled) {
    cursor: pointer;
    opacity: @linkOpacity;
    transition: opacity @defaultDuration @defaultEasing;
  }
  i.link.icon:hover, i.link.icons:hover {
    opacity: 1;
  }
}

& when (@variationIconCircular) {
  /*-------------------
        Circular
  --------------------*/

  i.circular.icon {
    border-radius: 500em !important;
    line-height: 1 !important;

    padding: @circularPadding !important;
    box-shadow: @circularShadow;

    width: @circularSize !important;
    height: @circularSize !important;
  }
  & when (@variationIconInverted) {
    i.circular.inverted.icon {
      border: none;
      box-shadow: none;
    }
  }
}

& when (@variationIconFlipped) {
  /*-------------------
        Flipped
  --------------------*/

  i.flipped.icon,
  i.horizontally.flipped.icon {
    transform: scale(-1, 1);
  }
  i.vertically.flipped.icon {
    transform: scale(1, -1);
  }
}

& when (@variationIconRotated) {
  /*-------------------
        Rotated
  --------------------*/

  i.rotated.icon,
  i.right.rotated.icon,
  i.clockwise.rotated.icon {
    transform: rotate(90deg);
  }

  i.left.rotated.icon,
  i.counterclockwise.rotated.icon {
    transform: rotate(-90deg);
  }

  i.halfway.rotated.icon {
    transform: rotate(180deg);
  }
}

& when (@variationIconFlipped) and (@variationIconRotated) {
  /*--------------------------
        Flipped & Rotated
  ---------------------------*/

  i.rotated.flipped.icon,
  i.right.rotated.flipped.icon,
  i.clockwise.rotated.flipped.icon {
    transform: scale(-1, 1) rotate(90deg);
  }

  i.left.rotated.flipped.icon,
  i.counterclockwise.rotated.flipped.icon {
    transform: scale(-1, 1) rotate(-90deg);
  }

  i.halfway.rotated.flipped.icon {
    transform: scale(-1, 1) rotate(180deg);
  }

  i.rotated.vertically.flipped.icon,
  i.right.rotated.vertically.flipped.icon,
  i.clockwise.rotated.vertically.flipped.icon {
    transform: scale(1, -1) rotate(90deg);
  }

  i.left.rotated.vertically.flipped.icon,
  i.counterclockwise.rotated.vertically.flipped.icon {
    transform: scale(1, -1) rotate(-90deg);
  }

  i.halfway.rotated.vertically.flipped.icon {
    transform: scale(1, -1) rotate(180deg);
  }
}

& when (@variationIconBordered) {
  /*-------------------
        Bordered
  --------------------*/

  i.bordered.icon {
    line-height: 1;
    vertical-align: baseline;

    width: @borderedSize;
    height: @borderedSize;
    padding: @borderedVerticalPadding @borderedHorizontalPadding !important;
    box-shadow: @borderedShadow;
  }
  & when (@variationIconInverted) {
    i.bordered.inverted.icon {
      border: none;
      box-shadow: none;
    }
  }
}

& when (@variationIconInverted) {
  /*-------------------
        Inverted
  --------------------*/

  /* Inverted Shapes */
  i.inverted.bordered.icon,
  i.inverted.circular.icon {
    background-color: @black;
    color: @white;
  }

  i.inverted.icon {
    color: @white;
  }
}

/*-------------------
       Colors
--------------------*/

each(@colors, {
  @color: replace(@key, '@', '');
  @c: @colors[@@color][color];
  @l: @colors[@@color][light];

  i.@{color}.icon.icon.icon.icon {
    color: @c;
  }
  & when (@variationIconInverted) {
    i.inverted.@{color}.icon.icon.icon.icon {
      color: @l;
    }
    i.inverted.bordered.@{color}.icon.icon.icon.icon,
    i.inverted.circular.@{color}.icon.icon.icon.icon {
      background-color: @c;
      color: @white;
    }
  }
})


/*-------------------
        Sizes
--------------------*/

i.icon,
i.icons {
  font-size: @medium;
  line-height: @lineHeight;
}
& when not (@variationIconSizes = false) {
  each(@variationIconSizes, {
    @s: @@value;
    i.@{value}.@{value}.@{value}.icon,
    i.@{value}.@{value}.@{value}.icons {
      font-size: @s;
      vertical-align: middle;
    }
  })
}

& when (@variationIconGroups) or (@variationIconCorner) {
  /*******************************
              Groups
  *******************************/

  i.icons {
    display: inline-block;
    position: relative;
    line-height: 1;
  }

  i.icons .icon {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translateX(-50%) translateY(-50%);
    margin: 0;
  }

  i.icons .icon:first-child {
    position: static;
    width: auto;
    height: auto;
    vertical-align: top;
    transform: none;
  }

  & when (@variationIconCorner) {
    /* Corner Icon */
    i.icons .corner.icon {
      top: auto;
      left: auto;
      right: 0;
      bottom: 0;
      transform: none;
      font-size: @cornerIconSize;
      text-shadow: @cornerIconShadow;
    }
    i.icons .icon.corner[class*="top right"] {
      top: 0;
      left: auto;
      right: 0;
      bottom: auto;
    }
    i.icons .icon.corner[class*="top left"] {
      top: 0;
      left: 0;
      right: auto;
      bottom: auto;
    }
    i.icons .icon.corner[class*="bottom left"] {
      top: auto;
      left: 0;
      right: auto;
      bottom: 0;
    }
    i.icons .icon.corner[class*="bottom right"] {
      top: auto;
      left: auto;
      right: 0;
      bottom: 0;
    }
    & when (@variationIconInverted) {
      i.icons .inverted.corner.icon {
        text-shadow: @cornerIconInvertedShadow;
      }
    }
  }
}

.loadUIOverrides();
EOT
        );

        @chmod($semantic_icon_definition_file, FILE_WRITE_MODE);

//------------------------------------------------------------------------------

        $semantic_icon_override_file = DEFAULTFCPATH.'assets/composer-asset/fomantic/ui/src/themes/default/elements/icon.overrides';

        file_put_contents($semantic_icon_override_file, <<<'EOT'
/*
* Font Awesome 5.13.0 by @fontawesome [https://fontawesome.com]
* License - https://fontawesome.com/license (Icons: CC BY 4.0 License, Fonts: SIL OFL 1.1 License, CSS: MIT License)
*/

/*******************************

Fomantic-UI integration of FontAwesome :

// class names are separated
i.icon.angle-left  =>  i.icon.angle.left

// variations are extracted
i.icon.circle      =>  i.icon.circle
i.icon.circle-o    =>  i.icon.circle.outline

// abbreviation are replaced by full words
i.icon.*-h         =>  i.icon.*.horizontal
i.icon.*-v         =>  i.icon.*.vertical
i.icon.alpha       =>  i.icon.alphabet
i.icon.asc         =>  i.icon.ascending
i.icon.desc        =>  i.icon.descending
i.icon.alt         =>  i.icon.alternate


Icons are order A-Z in their group, Solid, Outline, Thin (Pro only) and Brand

*******************************/


/*******************************
             Icons
*******************************/

/* Deprecated *In/Out Naming Conflict) */
i.icon.linkedin.in:before { content: "\f0e1"; }
i.icon.zoom.in:before { content: "\f00e"; }
i.icon.zoom.out:before { content: "\f010"; }
i.icon.sign.in:before { content: "\f2f6"; }
i.icon.in.cart:before { content: "\f218"; }
i.icon.log.out:before { content: "\f2f5"; }
i.icon.sign.out:before { content: "\f2f5"; }


/*******************************
          Solid Icons
*******************************/

/* Icons */
i.icon.ad:before { content: "\f641"; }
i.icon.address.book:before { content: "\f2b9"; }
i.icon.address.card:before { content: "\f2bb"; }
i.icon.adjust:before { content: "\f042"; }
i.icon.air.freshener:before { content: "\f5d0"; }
i.icon.align.center:before { content: "\f037"; }
i.icon.align.justify:before { content: "\f039"; }
i.icon.align.left:before { content: "\f036"; }
i.icon.align.right:before { content: "\f038"; }
i.icon.allergies:before { content: "\f461"; }
i.icon.ambulance:before { content: "\f0f9"; }
i.icon.american.sign.language.interpreting:before { content: "\f2a3"; }
i.icon.anchor:before { content: "\f13d"; }
i.icon.angle.double.down:before { content: "\f103"; }
i.icon.angle.double.left:before { content: "\f100"; }
i.icon.angle.double.right:before { content: "\f101"; }
i.icon.angle.double.up:before { content: "\f102"; }
i.icon.angle.down:before { content: "\f107"; }
i.icon.angle.left:before { content: "\f104"; }
i.icon.angle.right:before { content: "\f105"; }
i.icon.angle.up:before { content: "\f106"; }
i.icon.angry:before { content: "\f556"; }
i.icon.ankh:before { content: "\f644"; }
i.icon.archive:before { content: "\f187"; }
i.icon.archway:before { content: "\f557"; }
i.icon.arrow.alternate.circle.down:before { content: "\f358"; }
i.icon.arrow.alternate.circle.left:before { content: "\f359"; }
i.icon.arrow.alternate.circle.right:before { content: "\f35a"; }
i.icon.arrow.alternate.circle.up:before { content: "\f35b"; }
i.icon.arrow.circle.down:before { content: "\f0ab"; }
i.icon.arrow.circle.left:before { content: "\f0a8"; }
i.icon.arrow.circle.right:before { content: "\f0a9"; }
i.icon.arrow.circle.up:before { content: "\f0aa"; }
i.icon.arrow.left:before { content: "\f060"; }
i.icon.arrow.right:before { content: "\f061"; }
i.icon.arrow.up:before { content: "\f062"; }
i.icon.arrow.down:before { content: "\f063"; }
i.icon.arrows.alternate:before { content: "\f0b2"; }
i.icon.arrows.alternate.horizontal:before { content: "\f337"; }
i.icon.arrows.alternate.vertical:before { content: "\f338"; }
i.icon.assistive.listening.systems:before { content: "\f2a2"; }
i.icon.asterisk:before { content: "\f069"; }
i.icon.at:before { content: "\f1fa"; }
i.icon.atlas:before { content: "\f558"; }
i.icon.atom:before { content: "\f5d2"; }
i.icon.audio.description:before { content: "\f29e"; }
i.icon.award:before { content: "\f559"; }
i.icon.baby:before { content: "\f77c"; }
i.icon.baby.carriage:before { content: "\f77d"; }
i.icon.backspace:before { content: "\f55a"; }
i.icon.backward:before { content: "\f04a"; }
i.icon.bacon:before { content: "\f7e5"; }
i.icon.bahai:before { content: "\f666"; }
i.icon.balance.scale:before { content: "\f24e"; }
i.icon.balance.scale.left:before { content: "\f515"; }
i.icon.balance.scale.right:before { content: "\f516"; }
i.icon.ban:before { content: "\f05e"; }
i.icon.band.aid:before { content: "\f462"; }
i.icon.barcode:before { content: "\f02a"; }
i.icon.bars:before { content: "\f0c9"; }
i.icon.baseball.ball:before { content: "\f433"; }
i.icon.basketball.ball:before { content: "\f434"; }
i.icon.bath:before { content: "\f2cd"; }
i.icon.battery.empty:before { content: "\f244"; }
i.icon.battery.full:before { content: "\f240"; }
i.icon.battery.half:before { content: "\f242"; }
i.icon.battery.quarter:before { content: "\f243"; }
i.icon.battery.three.quarters:before { content: "\f241"; }
i.icon.bed:before { content: "\f236"; }
i.icon.beer:before { content: "\f0fc"; }
i.icon.bell:before { content: "\f0f3"; }
i.icon.bell.slash:before { content: "\f1f6"; }
i.icon.bezier.curve:before { content: "\f55b"; }
i.icon.bible:before { content: "\f647"; }
i.icon.bicycle:before { content: "\f206"; }
i.icon.biking:before { content: "\f84a"; }
i.icon.binoculars:before { content: "\f1e5"; }
i.icon.biohazard:before { content: "\f780"; }
i.icon.birthday.cake:before { content: "\f1fd"; }
i.icon.blender:before { content: "\f517"; }
i.icon.blender.phone:before { content: "\f6b6"; }
i.icon.blind:before { content: "\f29d"; }
i.icon.blog:before { content: "\f781"; }
i.icon.bold:before { content: "\f032"; }
i.icon.bolt:before { content: "\f0e7"; }
i.icon.bomb:before { content: "\f1e2"; }
i.icon.bone:before { content: "\f5d7"; }
i.icon.bong:before { content: "\f55c"; }
i.icon.book:before { content: "\f02d"; }
i.icon.book.dead:before { content: "\f6b7"; }
i.icon.book.medical:before { content: "\f7e6"; }
i.icon.book.open:before { content: "\f518"; }
i.icon.book.reader:before { content: "\f5da"; }
i.icon.bookmark:before { content: "\f02e"; }
i.icon.border.all:before { content: "\f84c"; }
i.icon.border.none:before { content: "\f850"; }
i.icon.border.style:before { content: "\f853"; }
i.icon.bowling.ball:before { content: "\f436"; }
i.icon.box:before { content: "\f466"; }
i.icon.box.open:before { content: "\f49e"; }
i.icon.box.tissue:before { content: "\f95b"; }
i.icon.boxes:before { content: "\f468"; }
i.icon.braille:before { content: "\f2a1"; }
i.icon.brain:before { content: "\f5dc"; }
i.icon.bread.slice:before { content: "\f7ec"; }
i.icon.briefcase:before { content: "\f0b1"; }
i.icon.briefcase.medical:before { content: "\f469"; }
i.icon.broadcast.tower:before { content: "\f519"; }
i.icon.broom:before { content: "\f51a"; }
i.icon.brush:before { content: "\f55d"; }
i.icon.bug:before { content: "\f188"; }
i.icon.building:before { content: "\f1ad"; }
i.icon.bullhorn:before { content: "\f0a1"; }
i.icon.bullseye:before { content: "\f140"; }
i.icon.burn:before { content: "\f46a"; }
i.icon.bus:before { content: "\f207"; }
i.icon.bus.alternate:before { content: "\f55e"; }
i.icon.business.time:before { content: "\f64a"; }
i.icon.calculator:before { content: "\f1ec"; }
i.icon.calendar:before { content: "\f133"; }
i.icon.calendar.alternate:before { content: "\f073"; }
i.icon.calendar.check:before { content: "\f274"; }
i.icon.calendar.day:before { content: "\f783"; }
i.icon.calendar.minus:before { content: "\f272"; }
i.icon.calendar.plus:before { content: "\f271"; }
i.icon.calendar.times:before { content: "\f273"; }
i.icon.calendar.week:before { content: "\f784"; }
i.icon.camera:before { content: "\f030"; }
i.icon.camera.retro:before { content: "\f083"; }
i.icon.campground:before { content: "\f6bb"; }
i.icon.candy.cane:before { content: "\f786"; }
i.icon.cannabis:before { content: "\f55f"; }
i.icon.capsules:before { content: "\f46b"; }
i.icon.car:before { content: "\f1b9"; }
i.icon.car.alternate:before { content: "\f5de"; }
i.icon.car.battery:before { content: "\f5df"; }
i.icon.car.crash:before { content: "\f5e1"; }
i.icon.car.side:before { content: "\f5e4"; }
i.icon.caravan:before { content: "\f8ff"; }
i.icon.caret.down:before { content: "\f0d7"; }
i.icon.caret.left:before { content: "\f0d9"; }
i.icon.caret.right:before { content: "\f0da"; }
i.icon.caret.square.down:before { content: "\f150"; }
i.icon.caret.square.left:before { content: "\f191"; }
i.icon.caret.square.right:before { content: "\f152"; }
i.icon.caret.square.up:before { content: "\f151"; }
i.icon.caret.up:before { content: "\f0d8"; }
i.icon.carrot:before { content: "\f787"; }
i.icon.cart.arrow.down:before { content: "\f218"; }
i.icon.cart.plus:before { content: "\f217"; }
i.icon.cash.register:before { content: "\f788"; }
i.icon.cat:before { content: "\f6be"; }
i.icon.certificate:before { content: "\f0a3"; }
i.icon.chair:before { content: "\f6c0"; }
i.icon.chalkboard:before { content: "\f51b"; }
i.icon.chalkboard.teacher:before { content: "\f51c"; }
i.icon.charging.station:before { content: "\f5e7"; }
i.icon.chart.area:before { content: "\f1fe"; }
i.icon.chart.bar:before { content: "\f080"; }
i.icon.chart.line:before { content: "\f201"; }
i.icon.chart.pie:before { content: "\f200"; }
i.icon.check:before { content: "\f00c"; }
i.icon.check.circle:before { content: "\f058"; }
i.icon.check.double:before { content: "\f560"; }
i.icon.check.square:before { content: "\f14a"; }
i.icon.cheese:before { content: "\f7ef"; }
i.icon.chess:before { content: "\f439"; }
i.icon.chess.bishop:before { content: "\f43a"; }
i.icon.chess.board:before { content: "\f43c"; }
i.icon.chess.king:before { content: "\f43f"; }
i.icon.chess.knight:before { content: "\f441"; }
i.icon.chess.pawn:before { content: "\f443"; }
i.icon.chess.queen:before { content: "\f445"; }
i.icon.chess.rook:before { content: "\f447"; }
i.icon.chevron.circle.down:before { content: "\f13a"; }
i.icon.chevron.circle.left:before { content: "\f137"; }
i.icon.chevron.circle.right:before { content: "\f138"; }
i.icon.chevron.circle.up:before { content: "\f139"; }
i.icon.chevron.down:before { content: "\f078"; }
i.icon.chevron.left:before { content: "\f053"; }
i.icon.chevron.right:before { content: "\f054"; }
i.icon.chevron.up:before { content: "\f077"; }
i.icon.child:before { content: "\f1ae"; }
i.icon.church:before { content: "\f51d"; }
i.icon.circle:before { content: "\f111"; }
i.icon.circle.notch:before { content: "\f1ce"; }
i.icon.city:before { content: "\f64f"; }
i.icon.clinic.medical:before { content: "\f7f2"; }
i.icon.clipboard:before { content: "\f328"; }
i.icon.clipboard.check:before { content: "\f46c"; }
i.icon.clipboard.list:before { content: "\f46d"; }
i.icon.clock:before { content: "\f017"; }
i.icon.clone:before { content: "\f24d"; }
i.icon.closed.captioning:before { content: "\f20a"; }
i.icon.cloud:before { content: "\f0c2"; }
i.icon.cloud.download.alternate:before { content: "\f381"; }
i.icon.cloud.meatball:before { content: "\f73b"; }
i.icon.cloud.moon:before { content: "\f6c3"; }
i.icon.cloud.moon.rain:before { content: "\f73c"; }
i.icon.cloud.rain:before { content: "\f73d"; }
i.icon.cloud.showers.heavy:before { content: "\f740"; }
i.icon.cloud.sun:before { content: "\f6c4"; }
i.icon.cloud.sun.rain:before { content: "\f743"; }
i.icon.cloud.upload.alternate:before { content: "\f382"; }
i.icon.cocktail:before { content: "\f561"; }
i.icon.code:before { content: "\f121"; }
i.icon.code.branch:before { content: "\f126"; }
i.icon.coffee:before { content: "\f0f4"; }
i.icon.cog:before { content: "\f013"; }
i.icon.cogs:before { content: "\f085"; }
i.icon.coins:before { content: "\f51e"; }
i.icon.columns:before { content: "\f0db"; }
i.icon.comment:before { content: "\f075"; }
i.icon.comment.alternate:before { content: "\f27a"; }
i.icon.comment.dollar:before { content: "\f651"; }
i.icon.comment.dots:before { content: "\f4ad"; }
i.icon.comment.medical:before { content: "\f7f5"; }
i.icon.comment.slash:before { content: "\f4b3"; }
i.icon.comments:before { content: "\f086"; }
i.icon.comments.dollar:before { content: "\f653"; }
i.icon.compact.disc:before { content: "\f51f"; }
i.icon.compass:before { content: "\f14e"; }
i.icon.compress:before { content: "\f066"; }
i.icon.compress.alternate:before { content: "\f422"; }
i.icon.compress.arrows.alternate:before { content: "\f78c"; }
i.icon.concierge.bell:before { content: "\f562"; }
i.icon.cookie:before { content: "\f563"; }
i.icon.cookie.bite:before { content: "\f564"; }
i.icon.copy:before { content: "\f0c5"; }
i.icon.copyright:before { content: "\f1f9"; }
i.icon.couch:before { content: "\f4b8"; }
i.icon.credit.card:before { content: "\f09d"; }
i.icon.crop:before { content: "\f125"; }
i.icon.crop.alternate:before { content: "\f565"; }
i.icon.cross:before { content: "\f654"; }
i.icon.crosshairs:before { content: "\f05b"; }
i.icon.crow:before { content: "\f520"; }
i.icon.crown:before { content: "\f521"; }
i.icon.crutch:before { content: "\f7f7"; }
i.icon.cube:before { content: "\f1b2"; }
i.icon.cubes:before { content: "\f1b3"; }
i.icon.cut:before { content: "\f0c4"; }
i.icon.database:before { content: "\f1c0"; }
i.icon.deaf:before { content: "\f2a4"; }
i.icon.democrat:before { content: "\f747"; }
i.icon.desktop:before { content: "\f108"; }
i.icon.dharmachakra:before { content: "\f655"; }
i.icon.diagnoses:before { content: "\f470"; }
i.icon.dice:before { content: "\f522"; }
i.icon.dice.d20:before { content: "\f6cf"; }
i.icon.dice.d6:before { content: "\f6d1"; }
i.icon.dice.five:before { content: "\f523"; }
i.icon.dice.four:before { content: "\f524"; }
i.icon.dice.one:before { content: "\f525"; }
i.icon.dice.six:before { content: "\f526"; }
i.icon.dice.three:before { content: "\f527"; }
i.icon.dice.two:before { content: "\f528"; }
i.icon.digital.tachograph:before { content: "\f566"; }
i.icon.directions:before { content: "\f5eb"; }
i.icon.disease:before { content: "\f7fa"; }
i.icon.divide:before { content: "\f529"; }
i.icon.dizzy:before { content: "\f567"; }
i.icon.dna:before { content: "\f471"; }
i.icon.dog:before { content: "\f6d3"; }
i.icon.dollar.sign:before { content: "\f155"; }
i.icon.dolly:before { content: "\f472"; }
i.icon.dolly.flatbed:before { content: "\f474"; }
i.icon.donate:before { content: "\f4b9"; }
i.icon.door.closed:before { content: "\f52a"; }
i.icon.door.open:before { content: "\f52b"; }
i.icon.dot.circle:before { content: "\f192"; }
i.icon.dove:before { content: "\f4ba"; }
i.icon.download:before { content: "\f019"; }
i.icon.drafting.compass:before { content: "\f568"; }
i.icon.dragon:before { content: "\f6d5"; }
i.icon.draw.polygon:before { content: "\f5ee"; }
i.icon.drum:before { content: "\f569"; }
i.icon.drum.steelpan:before { content: "\f56a"; }
i.icon.drumstick.bite:before { content: "\f6d7"; }
i.icon.dumbbell:before { content: "\f44b"; }
i.icon.dumpster:before { content: "\f793"; }
i.icon.dumpster.fire:before { content: "\f794"; }
i.icon.dungeon:before { content: "\f6d9"; }
i.icon.edit:before { content: "\f044"; }
i.icon.egg:before { content: "\f7fb"; }
i.icon.eject:before { content: "\f052"; }
i.icon.ellipsis.horizontal:before { content: "\f141"; }
i.icon.ellipsis.vertical:before { content: "\f142"; }
i.icon.envelope:before { content: "\f0e0"; }
i.icon.envelope.open:before { content: "\f2b6"; }
i.icon.envelope.open.text:before { content: "\f658"; }
i.icon.envelope.square:before { content: "\f199"; }
i.icon.equals:before { content: "\f52c"; }
i.icon.eraser:before { content: "\f12d"; }
i.icon.ethernet:before { content: "\f796"; }
i.icon.euro.sign:before { content: "\f153"; }
i.icon.exchange.alternate:before { content: "\f362"; }
i.icon.exclamation:before { content: "\f12a"; }
i.icon.exclamation.circle:before { content: "\f06a"; }
i.icon.exclamation.triangle:before { content: "\f071"; }
i.icon.expand:before { content: "\f065"; }
i.icon.expand.alternate:before { content: "\f424"; }
i.icon.expand.arrows.alternate:before { content: "\f31e"; }
i.icon.external.alternate:before { content: "\f35d"; }
i.icon.external.link.square.alternate:before { content: "\f360"; }
i.icon.eye:before { content: "\f06e"; }
i.icon.eye.dropper:before { content: "\f1fb"; }
i.icon.eye.slash:before { content: "\f070"; }
i.icon.fan:before { content: "\f863"; }
i.icon.fast.backward:before { content: "\f049"; }
i.icon.fast.forward:before { content: "\f050"; }
i.icon.faucet:before { content: "\f905"; }
i.icon.fax:before { content: "\f1ac"; }
i.icon.feather:before { content: "\f52d"; }
i.icon.feather.alternate:before { content: "\f56b"; }
i.icon.female:before { content: "\f182"; }
i.icon.fighter.jet:before { content: "\f0fb"; }
i.icon.file:before { content: "\f15b"; }
i.icon.file.alternate:before { content: "\f15c"; }
i.icon.file.archive:before { content: "\f1c6"; }
i.icon.file.audio:before { content: "\f1c7"; }
i.icon.file.code:before { content: "\f1c9"; }
i.icon.file.contract:before { content: "\f56c"; }
i.icon.file.csv:before { content: "\f6dd"; }
i.icon.file.download:before { content: "\f56d"; }
i.icon.file.excel:before { content: "\f1c3"; }
i.icon.file.export:before { content: "\f56e"; }
i.icon.file.image:before { content: "\f1c5"; }
i.icon.file.import:before { content: "\f56f"; }
i.icon.file.invoice:before { content: "\f570"; }
i.icon.file.invoice.dollar:before { content: "\f571"; }
i.icon.file.medical:before { content: "\f477"; }
i.icon.file.medical.alternate:before { content: "\f478"; }
i.icon.file.pdf:before { content: "\f1c1"; }
i.icon.file.powerpoint:before { content: "\f1c4"; }
i.icon.file.prescription:before { content: "\f572"; }
i.icon.file.signature:before { content: "\f573"; }
i.icon.file.upload:before { content: "\f574"; }
i.icon.file.video:before { content: "\f1c8"; }
i.icon.file.word:before { content: "\f1c2"; }
i.icon.fill:before { content: "\f575"; }
i.icon.fill.drip:before { content: "\f576"; }
i.icon.film:before { content: "\f008"; }
i.icon.filter:before { content: "\f0b0"; }
i.icon.fingerprint:before { content: "\f577"; }
i.icon.fire:before { content: "\f06d"; }
i.icon.fire.alternate:before { content: "\f7e4"; }
i.icon.fire.extinguisher:before { content: "\f134"; }
i.icon.first.aid:before { content: "\f479"; }
i.icon.fish:before { content: "\f578"; }
i.icon.fist.raised:before { content: "\f6de"; }
i.icon.flag:before { content: "\f024"; }
i.icon.flag.checkered:before { content: "\f11e"; }
i.icon.flag.usa:before { content: "\f74d"; }
i.icon.flask:before { content: "\f0c3"; }
i.icon.flushed:before { content: "\f579"; }
i.icon.folder:before { content: "\f07b"; }
i.icon.folder.minus:before { content: "\f65d"; }
i.icon.folder.open:before { content: "\f07c"; }
i.icon.folder.plus:before { content: "\f65e"; }
i.icon.font:before { content: "\f031"; }
i.icon.football.ball:before { content: "\f44e"; }
i.icon.forward:before { content: "\f04e"; }
i.icon.frog:before { content: "\f52e"; }
i.icon.frown:before { content: "\f119"; }
i.icon.frown.open:before { content: "\f57a"; }
i.icon.fruit-apple:before { content: "\f5d1"; }
i.icon.funnel.dollar:before { content: "\f662"; }
i.icon.futbol:before { content: "\f1e3"; }
i.icon.gamepad:before { content: "\f11b"; }
i.icon.gas.pump:before { content: "\f52f"; }
i.icon.gavel:before { content: "\f0e3"; }
i.icon.gem:before { content: "\f3a5"; }
i.icon.genderless:before { content: "\f22d"; }
i.icon.ghost:before { content: "\f6e2"; }
i.icon.gift:before { content: "\f06b"; }
i.icon.gifts:before { content: "\f79c"; }
i.icon.glass.cheers:before { content: "\f79f"; }
i.icon.glass.martini:before { content: "\f000"; }
i.icon.glass.martini.alternate:before { content: "\f57b"; }
i.icon.glass.whiskey:before { content: "\f7a0"; }
i.icon.glasses:before { content: "\f530"; }
i.icon.globe:before { content: "\f0ac"; }
i.icon.globe.africa:before { content: "\f57c"; }
i.icon.globe.americas:before { content: "\f57d"; }
i.icon.globe.asia:before { content: "\f57e"; }
i.icon.globe.europe:before { content: "\f7a2"; }
i.icon.golf.ball:before { content: "\f450"; }
i.icon.gopuram:before { content: "\f664"; }
i.icon.graduation.cap:before { content: "\f19d"; }
i.icon.greater.than:before { content: "\f531"; }
i.icon.greater.than.equal:before { content: "\f532"; }
i.icon.grimace:before { content: "\f57f"; }
i.icon.grin:before { content: "\f580"; }
i.icon.grin.alternate:before { content: "\f581"; }
i.icon.grin.beam:before { content: "\f582"; }
i.icon.grin.beam.sweat:before { content: "\f583"; }
i.icon.grin.hearts:before { content: "\f584"; }
i.icon.grin.squint:before { content: "\f585"; }
i.icon.grin.squint.tears:before { content: "\f586"; }
i.icon.grin.stars:before { content: "\f587"; }
i.icon.grin.tears:before { content: "\f588"; }
i.icon.grin.tongue:before { content: "\f589"; }
i.icon.grin.tongue.squint:before { content: "\f58a"; }
i.icon.grin.tongue.wink:before { content: "\f58b"; }
i.icon.grin.wink:before { content: "\f58c"; }
i.icon.grip.horizontal:before { content: "\f58d"; }
i.icon.grip.lines:before { content: "\f7a4"; }
i.icon.grip.lines.vertical:before { content: "\f7a5"; }
i.icon.grip.vertical:before { content: "\f58e"; }
i.icon.guitar:before { content: "\f7a6"; }
i.icon.h.square:before { content: "\f0fd"; }
i.icon.hamburger:before { content: "\f805"; }
i.icon.hammer:before { content: "\f6e3"; }
i.icon.hamsa:before { content: "\f665"; }
i.icon.hand.holding:before { content: "\f4bd"; }
i.icon.hand.holding.heart:before { content: "\f4be"; }
i.icon.hand.holding.medical:before { content: "\f95c"; }
i.icon.hand.holding.usd:before { content: "\f4c0"; }
i.icon.hand.holding.water:before { content: "\f4c1"; }
i.icon.hand.lizard:before { content: "\f258"; }
i.icon.hand.middle.finger:before { content: "\f806"; }
i.icon.hand.paper:before { content: "\f256"; }
i.icon.hand.peace:before { content: "\f25b"; }
i.icon.hand.point.down:before { content: "\f0a7"; }
i.icon.hand.point.left:before { content: "\f0a5"; }
i.icon.hand.point.right:before { content: "\f0a4"; }
i.icon.hand.point.up:before { content: "\f0a6"; }
i.icon.hand.pointer:before { content: "\f25a"; }
i.icon.hand.rock:before { content: "\f255"; }
i.icon.hand.scissors:before { content: "\f257"; }
i.icon.hand.sparkles:before { content: "\f95d"; }
i.icon.hand.spock:before { content: "\f259"; }
i.icon.hands:before { content: "\f4c2"; }
i.icon.hands.helping:before { content: "\f4c4"; }
i.icon.hands.wash:before { content: "\f95e"; }
i.icon.handshake:before { content: "\f2b5"; }
i.icon.handshake.alternate.slash:before { content: "\f95f"; }
i.icon.handshake.slash:before { content: "\f960"; }
i.icon.hanukiah:before { content: "\f6e6"; }
i.icon.hard.hat:before { content: "\f807"; }
i.icon.hashtag:before { content: "\f292"; }
i.icon.hat.cowboy:before { content: "\f8c0"; }
i.icon.hat.cowboy.side:before { content: "\f8c1"; }
i.icon.hat.wizard:before { content: "\f6e8"; }
i.icon.hdd:before { content: "\f0a0"; }
i.icon.head.side.cough:before { content: "\f961"; }
i.icon.head.side.cough.slash:before { content: "\f962"; }
i.icon.head.side.mask:before { content: "\f963"; }
i.icon.head.side.virus:before { content: "\f964"; }
i.icon.heading:before { content: "\f1dc"; }
i.icon.headphones:before { content: "\f025"; }
i.icon.headphones.alternate:before { content: "\f58f"; }
i.icon.headset:before { content: "\f590"; }
i.icon.heart:before { content: "\f004"; }
i.icon.heart.broken:before { content: "\f7a9"; }
i.icon.heartbeat:before { content: "\f21e"; }
i.icon.helicopter:before { content: "\f533"; }
i.icon.highlighter:before { content: "\f591"; }
i.icon.hiking:before { content: "\f6ec"; }
i.icon.hippo:before { content: "\f6ed"; }
i.icon.history:before { content: "\f1da"; }
i.icon.hockey.puck:before { content: "\f453"; }
i.icon.holly.berry:before { content: "\f7aa"; }
i.icon.home:before { content: "\f015"; }
i.icon.horse:before { content: "\f6f0"; }
i.icon.horse.head:before { content: "\f7ab"; }
i.icon.hospital:before { content: "\f0f8"; }
i.icon.hospital.alternate:before { content: "\f47d"; }
i.icon.hospital.symbol:before { content: "\f47e"; }
i.icon.hospital.user:before { content: "\f80d"; }
i.icon.hot.tub:before { content: "\f593"; }
i.icon.hotdog:before { content: "\f80f"; }
i.icon.hotel:before { content: "\f594"; }
i.icon.hourglass:before { content: "\f254"; }
i.icon.hourglass.end:before { content: "\f253"; }
i.icon.hourglass.half:before { content: "\f252"; }
i.icon.hourglass.start:before { content: "\f251"; }
i.icon.house.damage:before { content: "\f6f1"; }
i.icon.house.user:before { content: "\f965"; }
i.icon.hryvnia:before { content: "\f6f2"; }
i.icon.i.cursor:before { content: "\f246"; }
i.icon.ice.cream:before { content: "\f810"; }
i.icon.icicles:before { content: "\f7ad"; }
i.icon.icons:before { content: "\f86d"; }
i.icon.id.badge:before { content: "\f2c1"; }
i.icon.id.card:before { content: "\f2c2"; }
i.icon.id.card.alternate:before { content: "\f47f"; }
i.icon.igloo:before { content: "\f7ae"; }
i.icon.image:before { content: "\f03e"; }
i.icon.images:before { content: "\f302"; }
i.icon.inbox:before { content: "\f01c"; }
i.icon.indent:before { content: "\f03c"; }
i.icon.industry:before { content: "\f275"; }
i.icon.infinity:before { content: "\f534"; }
i.icon.info:before { content: "\f129"; }
i.icon.info.circle:before { content: "\f05a"; }
i.icon.italic:before { content: "\f033"; }
i.icon.jedi:before { content: "\f669"; }
i.icon.joint:before { content: "\f595"; }
i.icon.journal.whills:before { content: "\f66a"; }
i.icon.kaaba:before { content: "\f66b"; }
i.icon.key:before { content: "\f084"; }
i.icon.keyboard:before { content: "\f11c"; }
i.icon.khanda:before { content: "\f66d"; }
i.icon.kiss:before { content: "\f596"; }
i.icon.kiss.beam:before { content: "\f597"; }
i.icon.kiss.wink.heart:before { content: "\f598"; }
i.icon.kiwi.bird:before { content: "\f535"; }
i.icon.landmark:before { content: "\f66f"; }
i.icon.language:before { content: "\f1ab"; }
i.icon.laptop:before { content: "\f109"; }
i.icon.laptop.code:before { content: "\f5fc"; }
i.icon.laptop.house:before { content: "\f966"; }
i.icon.laptop.medical:before { content: "\f812"; }
i.icon.laugh:before { content: "\f599"; }
i.icon.laugh.beam:before { content: "\f59a"; }
i.icon.laugh.squint:before { content: "\f59b"; }
i.icon.laugh.wink:before { content: "\f59c"; }
i.icon.layer.group:before { content: "\f5fd"; }
i.icon.leaf:before { content: "\f06c"; }
i.icon.lemon:before { content: "\f094"; }
i.icon.less.than:before { content: "\f536"; }
i.icon.less.than.equal:before { content: "\f537"; }
i.icon.level.down.alternate:before { content: "\f3be"; }
i.icon.level.up.alternate:before { content: "\f3bf"; }
i.icon.life.ring:before { content: "\f1cd"; }
i.icon.lightbulb:before { content: "\f0eb"; }
i.icon.linkify:before { content: "\f0c1"; }
i.icon.lira.sign:before { content: "\f195"; }
i.icon.list:before { content: "\f03a"; }
i.icon.list.alternate:before { content: "\f022"; }
i.icon.list.ol:before { content: "\f0cb"; }
i.icon.list.ul:before { content: "\f0ca"; }
i.icon.location.arrow:before { content: "\f124"; }
i.icon.lock:before { content: "\f023"; }
i.icon.lock.open:before { content: "\f3c1"; }
i.icon.long.arrow.alternate.down:before { content: "\f309"; }
i.icon.long.arrow.alternate.left:before { content: "\f30a"; }
i.icon.long.arrow.alternate.right:before { content: "\f30b"; }
i.icon.long.arrow.alternate.up:before { content: "\f30c"; }
i.icon.low.vision:before { content: "\f2a8"; }
i.icon.luggage.cart:before { content: "\f59d"; }
i.icon.lungs:before { content: "\f604"; }
i.icon.lungs.virus:before { content: "\f967"; }
i.icon.magic:before { content: "\f0d0"; }
i.icon.magnet:before { content: "\f076"; }
i.icon.mail.bulk:before { content: "\f674"; }
i.icon.male:before { content: "\f183"; }
i.icon.map:before { content: "\f279"; }
i.icon.map.marked:before { content: "\f59f"; }
i.icon.map.marked.alternate:before { content: "\f5a0"; }
i.icon.map.marker:before { content: "\f041"; }
i.icon.map.marker.alternate:before { content: "\f3c5"; }
i.icon.map.pin:before { content: "\f276"; }
i.icon.map.signs:before { content: "\f277"; }
i.icon.marker:before { content: "\f5a1"; }
i.icon.mars:before { content: "\f222"; }
i.icon.mars.double:before { content: "\f227"; }
i.icon.mars.stroke:before { content: "\f229"; }
i.icon.mars.stroke.horizontal:before { content: "\f22b"; }
i.icon.mars.stroke.vertical:before { content: "\f22a"; }
i.icon.mask:before { content: "\f6fa"; }
i.icon.medal:before { content: "\f5a2"; }
i.icon.medkit:before { content: "\f0fa"; }
i.icon.meh:before { content: "\f11a"; }
i.icon.meh.blank:before { content: "\f5a4"; }
i.icon.meh.rolling.eyes:before { content: "\f5a5"; }
i.icon.memory:before { content: "\f538"; }
i.icon.menorah:before { content: "\f676"; }
i.icon.mercury:before { content: "\f223"; }
i.icon.meteor:before { content: "\f753"; }
i.icon.microchip:before { content: "\f2db"; }
i.icon.microphone:before { content: "\f130"; }
i.icon.microphone.alternate:before { content: "\f3c9"; }
i.icon.microphone.alternate.slash:before { content: "\f539"; }
i.icon.microphone.slash:before { content: "\f131"; }
i.icon.microscope:before { content: "\f610"; }
i.icon.minus:before { content: "\f068"; }
i.icon.minus.circle:before { content: "\f056"; }
i.icon.minus.square:before { content: "\f146"; }
i.icon.mitten:before { content: "\f7b5"; }
i.icon.mobile:before { content: "\f10b"; }
i.icon.mobile.alternate:before { content: "\f3cd"; }
i.icon.money.bill:before { content: "\f0d6"; }
i.icon.money.bill.alternate:before { content: "\f3d1"; }
i.icon.money.bill.wave:before { content: "\f53a"; }
i.icon.money.bill.wave.alternate:before { content: "\f53b"; }
i.icon.money.check:before { content: "\f53c"; }
i.icon.money.check.alternate:before { content: "\f53d"; }
i.icon.monument:before { content: "\f5a6"; }
i.icon.moon:before { content: "\f186"; }
i.icon.mortar.pestle:before { content: "\f5a7"; }
i.icon.mosque:before { content: "\f678"; }
i.icon.motorcycle:before { content: "\f21c"; }
i.icon.mountain:before { content: "\f6fc"; }
i.icon.mouse:before { content: "\f8cc"; }
i.icon.mouse.pointer:before { content: "\f245"; }
i.icon.mug.hot:before { content: "\f7b6"; }
i.icon.music:before { content: "\f001"; }
i.icon.network.wired:before { content: "\f6ff"; }
i.icon.neuter:before { content: "\f22c"; }
i.icon.newspaper:before { content: "\f1ea"; }
i.icon.not.equal:before { content: "\f53e"; }
i.icon.notes.medical:before { content: "\f481"; }
i.icon.object.group:before { content: "\f247"; }
i.icon.object.ungroup:before { content: "\f248"; }
i.icon.oil.can:before { content: "\f613"; }
i.icon.om:before { content: "\f679"; }
i.icon.otter:before { content: "\f700"; }
i.icon.outdent:before { content: "\f03b"; }
i.icon.pager:before { content: "\f815"; }
i.icon.paint.brush:before { content: "\f1fc"; }
i.icon.paint.roller:before { content: "\f5aa"; }
i.icon.palette:before { content: "\f53f"; }
i.icon.pallet:before { content: "\f482"; }
i.icon.paper.plane:before { content: "\f1d8"; }
i.icon.paperclip:before { content: "\f0c6"; }
i.icon.parachute.box:before { content: "\f4cd"; }
i.icon.paragraph:before { content: "\f1dd"; }
i.icon.parking:before { content: "\f540"; }
i.icon.passport:before { content: "\f5ab"; }
i.icon.pastafarianism:before { content: "\f67b"; }
i.icon.paste:before { content: "\f0ea"; }
i.icon.pause:before { content: "\f04c"; }
i.icon.pause.circle:before { content: "\f28b"; }
i.icon.paw:before { content: "\f1b0"; }
i.icon.peace:before { content: "\f67c"; }
i.icon.pen:before { content: "\f304"; }
i.icon.pen.alternate:before { content: "\f305"; }
i.icon.pen.fancy:before { content: "\f5ac"; }
i.icon.pen.nib:before { content: "\f5ad"; }
i.icon.pen.square:before { content: "\f14b"; }
i.icon.pencil.alternate:before { content: "\f303"; }
i.icon.pencil.ruler:before { content: "\f5ae"; }
i.icon.people.arrows:before { content: "\f968"; }
i.icon.people.carry:before { content: "\f4ce"; }
i.icon.pepper.hot:before { content: "\f816"; }
i.icon.percent:before { content: "\f295"; }
i.icon.percentage:before { content: "\f541"; }
i.icon.person.booth:before { content: "\f756"; }
i.icon.phone:before { content: "\f095"; }
i.icon.phone.alternate:before { content: "\f879"; }
i.icon.phone.slash:before { content: "\f3dd"; }
i.icon.phone.square:before { content: "\f098"; }
i.icon.phone.square.alternate:before { content: "\f87b"; }
i.icon.phone.volume:before { content: "\f2a0"; }
i.icon.photo.video:before { content: "\f87c"; }
i.icon.piggy.bank:before { content: "\f4d3"; }
i.icon.pills:before { content: "\f484"; }
i.icon.pizza.slice:before { content: "\f818"; }
i.icon.place.of.worship:before { content: "\f67f"; }
i.icon.plane:before { content: "\f072"; }
i.icon.plane.arrival:before { content: "\f5af"; }
i.icon.plane.departure:before { content: "\f5b0"; }
i.icon.plane.slash:before { content: "\f969"; }
i.icon.play:before { content: "\f04b"; }
i.icon.play.circle:before { content: "\f144"; }
i.icon.plug:before { content: "\f1e6"; }
i.icon.plus:before { content: "\f067"; }
i.icon.plus.circle:before { content: "\f055"; }
i.icon.plus.square:before { content: "\f0fe"; }
i.icon.podcast:before { content: "\f2ce"; }
i.icon.poll:before { content: "\f681"; }
i.icon.poll.horizontal:before { content: "\f682"; }
i.icon.poo:before { content: "\f2fe"; }
i.icon.poo.storm:before { content: "\f75a"; }
i.icon.poop:before { content: "\f619"; }
i.icon.portrait:before { content: "\f3e0"; }
i.icon.pound.sign:before { content: "\f154"; }
i.icon.power.off:before { content: "\f011"; }
i.icon.pray:before { content: "\f683"; }
i.icon.praying.hands:before { content: "\f684"; }
i.icon.prescription:before { content: "\f5b1"; }
i.icon.prescription.bottle:before { content: "\f485"; }
i.icon.prescription.bottle.alternate:before { content: "\f486"; }
i.icon.print:before { content: "\f02f"; }
i.icon.procedures:before { content: "\f487"; }
i.icon.project.diagram:before { content: "\f542"; }
i.icon.pump.medical:before { content: "\f96a"; }
i.icon.pump.soap:before { content: "\f96b"; }
i.icon.puzzle.piece:before { content: "\f12e"; }
i.icon.qrcode:before { content: "\f029"; }
i.icon.question:before { content: "\f128"; }
i.icon.question.circle:before { content: "\f059"; }
i.icon.quidditch:before { content: "\f458"; }
i.icon.quote.left:before { content: "\f10d"; }
i.icon.quote.right:before { content: "\f10e"; }
i.icon.quran:before { content: "\f687"; }
i.icon.radiation:before { content: "\f7b9"; }
i.icon.radiation.alternate:before { content: "\f7ba"; }
i.icon.rainbow:before { content: "\f75b"; }
i.icon.random:before { content: "\f074"; }
i.icon.receipt:before { content: "\f543"; }
i.icon.record.vinyl:before { content: "\f8d9"; }
i.icon.recycle:before { content: "\f1b8"; }
i.icon.redo:before { content: "\f01e"; }
i.icon.redo.alternate:before { content: "\f2f9"; }
i.icon.registered:before { content: "\f25d"; }
i.icon.remove.format:before { content: "\f87d"; }
i.icon.reply:before { content: "\f3e5"; }
i.icon.reply.all:before { content: "\f122"; }
i.icon.republican:before { content: "\f75e"; }
i.icon.restroom:before { content: "\f7bd"; }
i.icon.retweet:before { content: "\f079"; }
i.icon.ribbon:before { content: "\f4d6"; }
i.icon.ring:before { content: "\f70b"; }
i.icon.road:before { content: "\f018"; }
i.icon.robot:before { content: "\f544"; }
i.icon.rocket:before { content: "\f135"; }
i.icon.route:before { content: "\f4d7"; }
i.icon.rss:before { content: "\f09e"; }
i.icon.rss.square:before { content: "\f143"; }
i.icon.ruble.sign:before { content: "\f158"; }
i.icon.ruler:before { content: "\f545"; }
i.icon.ruler.combined:before { content: "\f546"; }
i.icon.ruler.horizontal:before { content: "\f547"; }
i.icon.ruler.vertical:before { content: "\f548"; }
i.icon.running:before { content: "\f70c"; }
i.icon.rupee.sign:before { content: "\f156"; }
i.icon.sad.cry:before { content: "\f5b3"; }
i.icon.sad.tear:before { content: "\f5b4"; }
i.icon.satellite:before { content: "\f7bf"; }
i.icon.satellite.dish:before { content: "\f7c0"; }
i.icon.save:before { content: "\f0c7"; }
i.icon.school:before { content: "\f549"; }
i.icon.screwdriver:before { content: "\f54a"; }
i.icon.scroll:before { content: "\f70e"; }
i.icon.sd.card:before { content: "\f7c2"; }
i.icon.search:before { content: "\f002"; }
i.icon.search.dollar:before { content: "\f688"; }
i.icon.search.location:before { content: "\f689"; }
i.icon.search.minus:before { content: "\f010"; }
i.icon.search.plus:before { content: "\f00e"; }
i.icon.seedling:before { content: "\f4d8"; }
i.icon.server:before { content: "\f233"; }
i.icon.shapes:before { content: "\f61f"; }
i.icon.share:before { content: "\f064"; }
i.icon.share.alternate:before { content: "\f1e0"; }
i.icon.share.alternate.square:before { content: "\f1e1"; }
i.icon.share.square:before { content: "\f14d"; }
i.icon.shekel.sign:before { content: "\f20b"; }
i.icon.shield.alternate:before { content: "\f3ed"; }
i.icon.shield.virus:before { content: "\f96c"; }
i.icon.ship:before { content: "\f21a"; }
i.icon.shipping.fast:before { content: "\f48b"; }
i.icon.shoe.prints:before { content: "\f54b"; }
i.icon.shopping.bag:before { content: "\f290"; }
i.icon.shopping.basket:before { content: "\f291"; }
i.icon.shopping.cart:before { content: "\f07a"; }
i.icon.shower:before { content: "\f2cc"; }
i.icon.shuttle.van:before { content: "\f5b6"; }
i.icon.sign:before { content: "\f4d9"; }
i.icon.sign.in.alternate:before { content: "\f2f6"; }
i.icon.sign.language:before { content: "\f2a7"; }
i.icon.sign.out.alternate:before { content: "\f2f5"; }
i.icon.signal:before { content: "\f012"; }
i.icon.signature:before { content: "\f5b7"; }
i.icon.sim.card:before { content: "\f7c4"; }
i.icon.sitemap:before { content: "\f0e8"; }
i.icon.skating:before { content: "\f7c5"; }
i.icon.skiing:before { content: "\f7c9"; }
i.icon.skiing.nordic:before { content: "\f7ca"; }
i.icon.skull:before { content: "\f54c"; }
i.icon.skull.crossbones:before { content: "\f714"; }
i.icon.slash:before { content: "\f715"; }
i.icon.sleigh:before { content: "\f7cc"; }
i.icon.sliders.horizontal:before { content: "\f1de"; }
i.icon.smile:before { content: "\f118"; }
i.icon.smile.beam:before { content: "\f5b8"; }
i.icon.smile.wink:before { content: "\f4da"; }
i.icon.smog:before { content: "\f75f"; }
i.icon.smoking:before { content: "\f48d"; }
i.icon.smoking.ban:before { content: "\f54d"; }
i.icon.sms:before { content: "\f7cd"; }
i.icon.snowboarding:before { content: "\f7ce"; }
i.icon.snowflake:before { content: "\f2dc"; }
i.icon.snowman:before { content: "\f7d0"; }
i.icon.snowplow:before { content: "\f7d2"; }
i.icon.soap:before { content: "\f96e"; }
i.icon.socks:before { content: "\f696"; }
i.icon.solar.panel:before { content: "\f5ba"; }
i.icon.sort:before { content: "\f0dc"; }
i.icon.sort.alphabet.down:before { content: "\f15d"; }
i.icon.sort.alphabet.down.alternate:before { content: "\f881"; }
i.icon.sort.alphabet.up:before { content: "\f15e"; }
i.icon.sort.alphabet.up.alternate:before { content: "\f882"; }
i.icon.sort.amount.down:before { content: "\f160"; }
i.icon.sort.amount.down.alternate:before { content: "\f884"; }
i.icon.sort.amount.up:before { content: "\f161"; }
i.icon.sort.amount.up.alternate:before { content: "\f885"; }
i.icon.sort.down:before { content: "\f0dd"; }
i.icon.sort.numeric.down:before { content: "\f162"; }
i.icon.sort.numeric.down.alternate:before { content: "\f886"; }
i.icon.sort.numeric.up:before { content: "\f163"; }
i.icon.sort.numeric.up.alternate:before { content: "\f887"; }
i.icon.sort.up:before { content: "\f0de"; }
i.icon.spa:before { content: "\f5bb"; }
i.icon.space.shuttle:before { content: "\f197"; }
i.icon.spell.check:before { content: "\f891"; }
i.icon.spider:before { content: "\f717"; }
i.icon.spinner:before { content: "\f110"; }
i.icon.splotch:before { content: "\f5bc"; }
i.icon.spray.can:before { content: "\f5bd"; }
i.icon.square:before { content: "\f0c8"; }
i.icon.square.full:before { content: "\f45c"; }
i.icon.square.root.alternate:before { content: "\f698"; }
i.icon.stamp:before { content: "\f5bf"; }
i.icon.star:before { content: "\f005"; }
i.icon.star.and.crescent:before { content: "\f699"; }
i.icon.star.half:before { content: "\f089"; }
i.icon.star.half.alternate:before { content: "\f5c0"; }
i.icon.star.of.david:before { content: "\f69a"; }
i.icon.star.of.life:before { content: "\f621"; }
i.icon.step.backward:before { content: "\f048"; }
i.icon.step.forward:before { content: "\f051"; }
i.icon.stethoscope:before { content: "\f0f1"; }
i.icon.sticky.note:before { content: "\f249"; }
i.icon.stop:before { content: "\f04d"; }
i.icon.stop.circle:before { content: "\f28d"; }
i.icon.stopwatch:before { content: "\f2f2"; }
i.icon.stopwatch.20:before { content: "\f96f"; }
i.icon.store:before { content: "\f54e"; }
i.icon.store.alternate:before { content: "\f54f"; }
i.icon.store.alternate.slash:before { content: "\f970"; }
i.icon.store.slash:before { content: "\f971"; }
i.icon.stream:before { content: "\f550"; }
i.icon.street.view:before { content: "\f21d"; }
i.icon.strikethrough:before { content: "\f0cc"; }
i.icon.stroopwafel:before { content: "\f551"; }
i.icon.subscript:before { content: "\f12c"; }
i.icon.subway:before { content: "\f239"; }
i.icon.suitcase:before { content: "\f0f2"; }
i.icon.suitcase.rolling:before { content: "\f5c1"; }
i.icon.sun:before { content: "\f185"; }
i.icon.superscript:before { content: "\f12b"; }
i.icon.surprise:before { content: "\f5c2"; }
i.icon.swatchbook:before { content: "\f5c3"; }
i.icon.swimmer:before { content: "\f5c4"; }
i.icon.swimming.pool:before { content: "\f5c5"; }
i.icon.synagogue:before { content: "\f69b"; }
i.icon.sync:before { content: "\f021"; }
i.icon.sync.alternate:before { content: "\f2f1"; }
i.icon.syringe:before { content: "\f48e"; }
i.icon.table:before { content: "\f0ce"; }
i.icon.table.tennis:before { content: "\f45d"; }
i.icon.tablet:before { content: "\f10a"; }
i.icon.tablet.alternate:before { content: "\f3fa"; }
i.icon.tablets:before { content: "\f490"; }
i.icon.tachometer.alternate:before { content: "\f3fd"; }
i.icon.tag:before { content: "\f02b"; }
i.icon.tags:before { content: "\f02c"; }
i.icon.tape:before { content: "\f4db"; }
i.icon.tasks:before { content: "\f0ae"; }
i.icon.taxi:before { content: "\f1ba"; }
i.icon.teeth:before { content: "\f62e"; }
i.icon.teeth.open:before { content: "\f62f"; }
i.icon.temperature.high:before { content: "\f769"; }
i.icon.temperature.low:before { content: "\f76b"; }
i.icon.tenge:before { content: "\f7d7"; }
i.icon.terminal:before { content: "\f120"; }
i.icon.text.height:before { content: "\f034"; }
i.icon.text.width:before { content: "\f035"; }
i.icon.th:before { content: "\f00a"; }
i.icon.th.large:before { content: "\f009"; }
i.icon.th.list:before { content: "\f00b"; }
i.icon.theater.masks:before { content: "\f630"; }
i.icon.thermometer:before { content: "\f491"; }
i.icon.thermometer.empty:before { content: "\f2cb"; }
i.icon.thermometer.full:before { content: "\f2c7"; }
i.icon.thermometer.half:before { content: "\f2c9"; }
i.icon.thermometer.quarter:before { content: "\f2ca"; }
i.icon.thermometer.three.quarters:before { content: "\f2c8"; }
i.icon.thumbs.down:before { content: "\f165"; }
i.icon.thumbs.up:before { content: "\f164"; }
i.icon.thumbtack:before { content: "\f08d"; }
i.icon.ticket.alternate:before { content: "\f3ff"; }
i.icon.times:before { content: "\f00d"; }
i.icon.times.circle:before { content: "\f057"; }
i.icon.tint:before { content: "\f043"; }
i.icon.tint.slash:before { content: "\f5c7"; }
i.icon.tired:before { content: "\f5c8"; }
i.icon.toggle.off:before { content: "\f204"; }
i.icon.toggle.on:before { content: "\f205"; }
i.icon.toilet:before { content: "\f7d8"; }
i.icon.toilet.paper:before { content: "\f71e"; }
i.icon.toilet.paper.slash:before { content: "\f972"; }
i.icon.toolbox:before { content: "\f552"; }
i.icon.tools:before { content: "\f7d9"; }
i.icon.tooth:before { content: "\f5c9"; }
i.icon.torah:before { content: "\f6a0"; }
i.icon.torii.gate:before { content: "\f6a1"; }
i.icon.tractor:before { content: "\f722"; }
i.icon.trademark:before { content: "\f25c"; }
i.icon.traffic.light:before { content: "\f637"; }
i.icon.trailer:before { content: "\f941"; }
i.icon.train:before { content: "\f238"; }
i.icon.tram:before { content: "\f7da"; }
i.icon.transgender:before { content: "\f224"; }
i.icon.transgender.alternate:before { content: "\f225"; }
i.icon.trash:before { content: "\f1f8"; }
i.icon.trash.alternate:before { content: "\f2ed"; }
i.icon.trash.restore:before { content: "\f829"; }
i.icon.trash.restore.alternate:before { content: "\f82a"; }
i.icon.tree:before { content: "\f1bb"; }
i.icon.trophy:before { content: "\f091"; }
i.icon.truck:before { content: "\f0d1"; }
i.icon.truck.monster:before { content: "\f63b"; }
i.icon.truck.moving:before { content: "\f4df"; }
i.icon.truck.packing:before { content: "\f4de"; }
i.icon.truck.pickup:before { content: "\f63c"; }
i.icon.tshirt:before { content: "\f553"; }
i.icon.tty:before { content: "\f1e4"; }
i.icon.tv:before { content: "\f26c"; }
i.icon.umbrella:before { content: "\f0e9"; }
i.icon.umbrella.beach:before { content: "\f5ca"; }
i.icon.underline:before { content: "\f0cd"; }
i.icon.undo:before { content: "\f0e2"; }
i.icon.undo.alternate:before { content: "\f2ea"; }
i.icon.universal.access:before { content: "\f29a"; }
i.icon.university:before { content: "\f19c"; }
i.icon.unlink:before { content: "\f127"; }
i.icon.unlock:before { content: "\f09c"; }
i.icon.unlock.alternate:before { content: "\f13e"; }
i.icon.upload:before { content: "\f093"; }
i.icon.user:before { content: "\f007"; }
i.icon.user.alternate:before { content: "\f406"; }
i.icon.user.alternate.slash:before { content: "\f4fa"; }
i.icon.user.astronaut:before { content: "\f4fb"; }
i.icon.user.check:before { content: "\f4fc"; }
i.icon.user.circle:before { content: "\f2bd"; }
i.icon.user.clock:before { content: "\f4fd"; }
i.icon.user.cog:before { content: "\f4fe"; }
i.icon.user.edit:before { content: "\f4ff"; }
i.icon.user.friends:before { content: "\f500"; }
i.icon.user.graduate:before { content: "\f501"; }
i.icon.user.injured:before { content: "\f728"; }
i.icon.user.lock:before { content: "\f502"; }
i.icon.user.md:before { content: "\f0f0"; }
i.icon.user.minus:before { content: "\f503"; }
i.icon.user.ninja:before { content: "\f504"; }
i.icon.user.nurse:before { content: "\f82f"; }
i.icon.user.plus:before { content: "\f234"; }
i.icon.user.secret:before { content: "\f21b"; }
i.icon.user.shield:before { content: "\f505"; }
i.icon.user.slash:before { content: "\f506"; }
i.icon.user.tag:before { content: "\f507"; }
i.icon.user.tie:before { content: "\f508"; }
i.icon.user.times:before { content: "\f235"; }
i.icon.users:before { content: "\f0c0"; }
i.icon.users.cog:before { content: "\f509"; }
i.icon.utensil.spoon:before { content: "\f2e5"; }
i.icon.utensils:before { content: "\f2e7"; }
i.icon.vector.square:before { content: "\f5cb"; }
i.icon.venus:before { content: "\f221"; }
i.icon.venus.double:before { content: "\f226"; }
i.icon.venus.mars:before { content: "\f228"; }
i.icon.vial:before { content: "\f492"; }
i.icon.vials:before { content: "\f493"; }
i.icon.video:before { content: "\f03d"; }
i.icon.video.slash:before { content: "\f4e2"; }
i.icon.vihara:before { content: "\f6a7"; }
i.icon.virus:before { content: "\f974"; }
i.icon.virus.slash:before { content: "\f975"; }
i.icon.viruses:before { content: "\f976"; }
i.icon.voicemail:before { content: "\f897"; }
i.icon.volleyball.ball:before { content: "\f45f"; }
i.icon.volume.down:before { content: "\f027"; }
i.icon.volume.mute:before { content: "\f6a9"; }
i.icon.volume.off:before { content: "\f026"; }
i.icon.volume.up:before { content: "\f028"; }
i.icon.vote.yea:before { content: "\f772"; }
i.icon.vr.cardboard:before { content: "\f729"; }
i.icon.walking:before { content: "\f554"; }
i.icon.wallet:before { content: "\f555"; }
i.icon.warehouse:before { content: "\f494"; }
i.icon.water:before { content: "\f773"; }
i.icon.wave.square:before { content: "\f83e"; }
i.icon.weight:before { content: "\f496"; }
i.icon.weight.hanging:before { content: "\f5cd"; }
i.icon.wheelchair:before { content: "\f193"; }
i.icon.wifi:before { content: "\f1eb"; }
i.icon.wind:before { content: "\f72e"; }
i.icon.window.close:before { content: "\f410"; }
i.icon.window.maximize:before { content: "\f2d0"; }
i.icon.window.minimize:before { content: "\f2d1"; }
i.icon.window.restore:before { content: "\f2d2"; }
i.icon.wine.bottle:before { content: "\f72f"; }
i.icon.wine.glass:before { content: "\f4e3"; }
i.icon.wine.glass.alternate:before { content: "\f5ce"; }
i.icon.won.sign:before { content: "\f159"; }
i.icon.wrench:before { content: "\f0ad"; }
i.icon.x.ray:before { content: "\f497"; }
i.icon.yen.sign:before { content: "\f157"; }
i.icon.yin.yang:before { content: "\f6ad"; }

/* Aliases */
i.icon.add:before { content: "\f067"; }
i.icon.add.circle:before { content: "\f055"; }
i.icon.add.square:before { content: "\f0fe"; }
i.icon.add.to.calendar:before { content: "\f271"; }
i.icon.add.to.cart:before { content: "\f217"; }
i.icon.add.user:before { content: "\f234"; }
i.icon.alarm:before { content: "\f0f3"; }
i.icon.alarm.mute:before { content: "\f1f6"; }
i.icon.ald:before { content: "\f2a2"; }
i.icon.als:before { content: "\f2a2"; }
i.icon.announcement:before { content: "\f0a1"; }
i.icon.area.chart:before { content: "\f1fe"; }
i.icon.area.graph:before { content: "\f1fe"; }
i.icon.arrow.down.cart:before { content: "\f218"; }
i.icon.asexual:before { content: "\f22d"; }
i.icon.asl:before { content: "\f2a3"; }
i.icon.asl.interpreting:before { content: "\f2a3"; }
i.icon.assistive.listening.devices:before { content: "\f2a2"; }
i.icon.attach:before { content: "\f0c6"; }
i.icon.attention:before { content: "\f06a"; }
i.icon.balance:before { content: "\f24e"; }
i.icon.bar:before { content: "\f0fc"; }
i.icon.bathtub:before { content: "\f2cd"; }
i.icon.battery.four:before { content: "\f240"; }
i.icon.battery.high:before { content: "\f241"; }
i.icon.battery.low:before { content: "\f243"; }
i.icon.battery.medium:before { content: "\f242"; }
i.icon.battery.one:before { content: "\f243"; }
i.icon.battery.three:before { content: "\f241"; }
i.icon.battery.two:before { content: "\f242"; }
i.icon.battery.zero:before { content: "\f244"; }
i.icon.birthday:before { content: "\f1fd"; }
i.icon.block.layout:before { content: "\f009"; }
i.icon.broken.chain:before { content: "\f127"; }
i.icon.browser:before { content: "\f022"; }
i.icon.call:before { content: "\f095"; }
i.icon.call.square:before { content: "\f098"; }
i.icon.cancel:before { content: "\f00d"; }
i.icon.cart:before { content: "\f07a"; }
i.icon.cc:before { content: "\f20a"; }
i.icon.chain:before { content: "\f0c1"; }
i.icon.chat:before { content: "\f075"; }
i.icon.checked.calendar:before { content: "\f274"; }
i.icon.checkmark:before { content: "\f00c"; }
i.icon.checkmark.box:before { content: "\f14a"; }
i.icon.chess.rock:before { content: "\f447"; }
i.icon.circle.notched:before { content: "\f1ce"; }
i.icon.circle.thin:before { content: "\f111"; }
i.icon.close:before { content: "\f00d"; }
i.icon.cloud.download:before { content: "\f381"; }
i.icon.cloud.upload:before { content: "\f382"; }
i.icon.cny:before { content: "\f157"; }
i.icon.cocktail:before { content: "\f000"; }
i.icon.commenting:before { content: "\f27a"; }
i.icon.compose:before { content: "\f303"; }
i.icon.computer:before { content: "\f108"; }
i.icon.configure:before { content: "\f0ad"; }
i.icon.content:before { content: "\f0c9"; }
i.icon.conversation:before { content: "\f086"; }
i.icon.credit.card.alternative:before { content: "\f09d"; }
i.icon.currency:before { content: "\f3d1"; }
i.icon.dashboard:before { content: "\f3fd"; }
i.icon.deafness:before { content: "\f2a4"; }
i.icon.delete:before { content: "\f00d"; }
i.icon.delete.calendar:before { content: "\f273"; }
i.icon.detective:before { content: "\f21b"; }
i.icon.diamond:before { content: "\f3a5"; }
i.icon.discussions:before { content: "\f086"; }
i.icon.disk:before { content: "\f0a0"; }
i.icon.doctor:before { content: "\f0f0"; }
i.icon.dollar:before { content: "\f155"; }
i.icon.dont:before { content: "\f05e"; }
i.icon.drivers.license:before { content: "\f2c2"; }
i.icon.dropdown:before { content: "\f0d7"; }
i.icon.emergency:before { content: "\f0f9"; }
i.icon.erase:before { content: "\f12d"; }
i.icon.eur:before { content: "\f153"; }
i.icon.euro:before { content: "\f153"; }
i.icon.exchange:before { content: "\f362"; }
i.icon.external:before { content: "\f35d"; }
i.icon.external.share:before { content: "\f14d"; }
i.icon.external.square:before { content: "\f360"; }
i.icon.eyedropper:before { content: "\f1fb"; }
i.icon.factory:before { content: "\f275"; }
i.icon.favorite:before { content: "\f005"; }
i.icon.feed:before { content: "\f09e"; }
i.icon.female.homosexual:before { content: "\f226"; }
i.icon.file.text:before { content: "\f15c"; }
i.icon.find:before { content: "\f1e5"; }
i.icon.first.aid:before { content: "\f0fa"; }
i.icon.food:before { content: "\f2e7"; }
i.icon.fork:before { content: "\f126"; }
i.icon.game:before { content: "\f11b"; }
i.icon.gay:before { content: "\f227"; }
i.icon.gbp:before { content: "\f154"; }
i.icon.grab:before { content: "\f255"; }
i.icon.graduation:before { content: "\f19d"; }
i.icon.grid.layout:before { content: "\f00a"; }
i.icon.group:before { content: "\f0c0"; }
i.icon.h:before { content: "\f0fd"; }
i.icon.hamburger:before { content: "\f0c9"; }
i.icon.hand.victory:before { content: "\f25b"; }
i.icon.handicap:before { content: "\f193"; }
i.icon.hard.of.hearing:before { content: "\f2a4"; }
i.icon.header:before { content: "\f1dc"; }
i.icon.heart.empty:before { content: "\f004"; }
i.icon.help:before { content: "\f128"; }
i.icon.help.circle:before { content: "\f059"; }
i.icon.heterosexual:before { content: "\f228"; }
i.icon.hide:before { content: "\f070"; }
i.icon.hotel:before { content: "\f236"; }
i.icon.hourglass.four:before { content: "\f254"; }
i.icon.hourglass.full:before { content: "\f254"; }
i.icon.hourglass.one:before { content: "\f251"; }
i.icon.hourglass.three:before { content: "\f253"; }
i.icon.hourglass.two:before { content: "\f252"; }
i.icon.hourglass.zero:before { content: "\f253"; }
i.icon.idea:before { content: "\f0eb"; }
i.icon.ils:before { content: "\f20b"; }
i.icon.in.cart:before { content: "\f218"; }
i.icon.inr:before { content: "\f156"; }
i.icon.intergender:before { content: "\f224"; }
i.icon.intersex:before { content: "\f224"; }
i.icon.jpy:before { content: "\f157"; }
i.icon.krw:before { content: "\f159"; }
i.icon.lab:before { content: "\f0c3"; }
i.icon.law:before { content: "\f24e"; }
i.icon.legal:before { content: "\f0e3"; }
i.icon.lesbian:before { content: "\f226"; }
i.icon.level.down:before { content: "\f3be"; }
i.icon.level.up:before { content: "\f3bf"; }
i.icon.lightning:before { content: "\f0e7"; }
i.icon.like:before { content: "\f004"; }
i.icon.line.graph:before { content: "\f201"; }
i.icon.linkify:before { content: "\f0c1"; }
i.icon.lira:before { content: "\f195"; }
i.icon.list.layout:before { content: "\f00b"; }
i.icon.log.out:before { content: "\f2f5"; }
i.icon.magnify:before { content: "\f00e"; }
i.icon.mail:before { content: "\f0e0"; }
i.icon.mail.forward:before { content: "\f064"; }
i.icon.mail.square:before { content: "\f199"; }
i.icon.male.homosexual:before { content: "\f227"; }
i.icon.man:before { content: "\f222"; }
i.icon.marker:before { content: "\f041"; }
i.icon.mars.alternate:before { content: "\f229"; }
i.icon.mars.horizontal:before { content: "\f22b"; }
i.icon.mars.vertical:before { content: "\f22a"; }
i.icon.meanpath:before { content: "\f0c8"; }
i.icon.military:before { content: "\f0fb"; }
i.icon.money:before { content: "\f3d1"; }
i.icon.move:before { content: "\f0b2"; }
i.icon.mute:before { content: "\f131"; }
i.icon.non.binary.transgender:before { content: "\f223"; }
i.icon.numbered.list:before { content: "\f0cb"; }
i.icon.options:before { content: "\f1de"; }
i.icon.ordered.list:before { content: "\f0cb"; }
i.icon.other.gender:before { content: "\f229"; }
i.icon.other.gender.horizontal:before { content: "\f22b"; }
i.icon.other.gender.vertical:before { content: "\f22a"; }
i.icon.payment:before { content: "\f09d"; }
i.icon.pencil:before { content: "\f303"; }
i.icon.pencil.square:before { content: "\f14b"; }
i.icon.photo:before { content: "\f030"; }
i.icon.picture:before { content: "\f03e"; }
i.icon.pie.chart:before { content: "\f200"; }
i.icon.pie.graph:before { content: "\f200"; }
i.icon.pin:before { content: "\f08d"; }
i.icon.plus.cart:before { content: "\f217"; }
i.icon.point:before { content: "\f041"; }
i.icon.pointing.down:before { content: "\f0a7"; }
i.icon.pointing.left:before { content: "\f0a5"; }
i.icon.pointing.right:before { content: "\f0a4"; }
i.icon.pointing.up:before { content: "\f0a6"; }
i.icon.pound:before { content: "\f154"; }
i.icon.power:before { content: "\f011"; }
i.icon.power.cord:before { content: "\f1e6"; }
i.icon.privacy:before { content: "\f084"; }
i.icon.protect:before { content: "\f023"; }
i.icon.puzzle:before { content: "\f12e"; }
i.icon.r.circle:before { content: "\f25d"; }
i.icon.radio:before { content: "\f192"; }
i.icon.rain:before { content: "\f0e9"; }
i.icon.record:before { content: "\f03d"; }
i.icon.refresh:before { content: "\f021"; }
i.icon.remove:before { content: "\f00d"; }
i.icon.remove.bookmark:before { content: "\f02e"; }
i.icon.remove.circle:before { content: "\f057"; }
i.icon.remove.from.calendar:before { content: "\f272"; }
i.icon.remove.user:before { content: "\f235"; }
i.icon.repeat:before { content: "\f01e"; }
i.icon.resize.horizontal:before { content: "\f337"; }
i.icon.resize.vertical:before { content: "\f338"; }
i.icon.rmb:before { content: "\f157"; }
i.icon.rouble:before { content: "\f158"; }
i.icon.rub:before { content: "\f158"; }
i.icon.ruble:before { content: "\f158"; }
i.icon.rupee:before { content: "\f156"; }
i.icon.s15:before { content: "\f2cd"; }
i.icon.selected.radio:before { content: "\f192"; }
i.icon.send:before { content: "\f1d8"; }
i.icon.setting:before { content: "\f013"; }
i.icon.settings:before { content: "\f085"; }
i.icon.shekel:before { content: "\f20b"; }
i.icon.sheqel:before { content: "\f20b"; }
i.icon.shield:before { content: "\f3ed"; }
i.icon.shipping:before { content: "\f0d1"; }
i.icon.shop:before { content: "\f07a"; }
i.icon.shuffle:before { content: "\f074"; }
i.icon.shutdown:before { content: "\f011"; }
i.icon.sidebar:before { content: "\f0c9"; }
i.icon.sign.in:before { content: "\f2f6"; }
i.icon.sign.out:before { content: "\f2f5"; }
i.icon.signing:before { content: "\f2a7"; }
i.icon.signup:before { content: "\f044"; }
i.icon.sliders:before { content: "\f1de"; }
i.icon.soccer:before { content: "\f1e3"; }
i.icon.sort.alphabet.ascending:before { content: "\f15d"; }
i.icon.sort.alphabet.descending:before { content: "\f15e"; }
i.icon.sort.ascending:before { content: "\f0de"; }
i.icon.sort.content.ascending:before { content: "\f160"; }
i.icon.sort.content.descending:before { content: "\f161"; }
i.icon.sort.descending:before { content: "\f0dd"; }
i.icon.sort.numeric.ascending:before { content: "\f162"; }
i.icon.sort.numeric.descending:before { content: "\f163"; }
i.icon.sound:before { content: "\f025"; }
i.icon.spoon:before { content: "\f2e5"; }
i.icon.spy:before { content: "\f21b"; }
i.icon.star.empty:before { content: "\f005"; }
i.icon.star.half.empty:before { content: "\f089"; }
i.icon.star.half.full:before { content: "\f089"; }
i.icon.student:before { content: "\f19d"; }
i.icon.talk:before { content: "\f27a"; }
i.icon.target:before { content: "\f140"; }
i.icon.teletype:before { content: "\f1e4"; }
i.icon.television:before { content: "\f26c"; }
i.icon.text.cursor:before { content: "\f246"; }
i.icon.text.telephone:before { content: "\f1e4"; }
i.icon.theme:before { content: "\f043"; }
i.icon.thermometer:before { content: "\f2c7"; }
i.icon.thumb.tack:before { content: "\f08d"; }
i.icon.ticket:before { content: "\f3ff"; }
i.icon.time:before { content: "\f017"; }
i.icon.times.rectangle:before { content: "\f410"; }
i.icon.tm:before { content: "\f25c"; }
i.icon.toggle.down:before { content: "\f150"; }
i.icon.toggle.left:before { content: "\f191"; }
i.icon.toggle.right:before { content: "\f152"; }
i.icon.toggle.up:before { content: "\f151"; }
i.icon.translate:before { content: "\f1ab"; }
i.icon.travel:before { content: "\f0b1"; }
i.icon.treatment:before { content: "\f0f1"; }
i.icon.triangle.down:before { content: "\f0d7"; }
i.icon.triangle.left:before { content: "\f0d9"; }
i.icon.triangle.right:before { content: "\f0da"; }
i.icon.triangle.up:before { content: "\f0d8"; }
i.icon.try:before { content: "\f195"; }
i.icon.unhide:before { content: "\f06e"; }
i.icon.unlinkify:before { content: "\f127"; }
i.icon.unmute:before { content: "\f130"; }
i.icon.unordered.list:before { content: "\f0ca"; }
i.icon.usd:before { content: "\f155"; }
i.icon.user.cancel:before { content: "\f235"; }
i.icon.user.close:before { content: "\f235"; }
i.icon.user.delete:before { content: "\f235"; }
i.icon.user.doctor:before { content: "\f0f0"; }
i.icon.user.x:before { content: "\f235"; }
i.icon.vcard:before { content: "\f2bb"; }
i.icon.video.camera:before { content: "\f03d"; }
i.icon.video.play:before { content: "\f144"; }
i.icon.volume.control.phone:before { content: "\f2a0"; }
i.icon.wait:before { content: "\f017"; }
i.icon.warning:before { content: "\f12a"; }
i.icon.warning.circle:before { content: "\f06a"; }
i.icon.warning.sign:before { content: "\f071"; }
i.icon.wi.fi:before { content: "\f1eb"; }
i.icon.winner:before { content: "\f091"; }
i.icon.wizard:before { content: "\f0d0"; }
i.icon.woman:before { content: "\f221"; }
i.icon.won:before { content: "\f159"; }
i.icon.world:before { content: "\f0ac"; }
i.icon.write:before { content: "\f303"; }
i.icon.write.square:before { content: "\f14b"; }
i.icon.x:before { content: "\f00d"; }
i.icon.yen:before { content: "\f157"; }
i.icon.zip:before { content: "\f187"; }
i.icon.zoom:before { content: "\f00e"; }
i.icon.zoom.in:before { content: "\f00e"; }
i.icon.zoom.out:before { content: "\f010"; }

/*******************************
         Outline Icons
*******************************/

/* Outline Icon */
.loadOutlineIcons() when (@importOutlineIcons) {
  /* Load & Define Icon Font */
  /*
  @font-face {
    font-family: @outlineFontName;
    src: @outlineFallbackSRC;
    src: @outlineSrc;
    font-style: normal;
    font-weight: @normal;
    font-variant: normal;
    text-decoration: inherit;
    text-transform: none;
  }
  */

  i.icon.outline {
    font-family: @outlineFontName;
  }

  /* Icons */
  i.icon.address.book.outline:before { content: "\f2b9"; }
  i.icon.address.card.outline:before { content: "\f2bb"; }
  i.icon.angry.outline:before { content: "\f556"; }
  i.icon.arrow.alternate.circle.down.outline:before { content: "\f358"; }
  i.icon.arrow.alternate.circle.left.outline:before { content: "\f359"; }
  i.icon.arrow.alternate.circle.right.outline:before { content: "\f35a"; }
  i.icon.arrow.alternate.circle.up.outline:before { content: "\f35b"; }
  i.icon.bell.outline:before { content: "\f0f3"; }
  i.icon.bell.slash.outline:before { content: "\f1f6"; }
  i.icon.bookmark.outline:before { content: "\f02e"; }
  i.icon.building.outline:before { content: "\f1ad"; }
  i.icon.calendar.alternate.outline:before { content: "\f073"; }
  i.icon.calendar.check.outline:before { content: "\f274"; }
  i.icon.calendar.minus.outline:before { content: "\f272"; }
  i.icon.calendar.outline:before { content: "\f133"; }
  i.icon.calendar.plus.outline:before { content: "\f271"; }
  i.icon.calendar.times.outline:before { content: "\f273"; }
  i.icon.caret.square.down.outline:before { content: "\f150"; }
  i.icon.caret.square.left.outline:before { content: "\f191"; }
  i.icon.caret.square.right.outline:before { content: "\f152"; }
  i.icon.caret.square.up.outline:before { content: "\f151"; }
  i.icon.chart.bar.outline:before { content: "\f080"; }
  i.icon.check.circle.outline:before { content: "\f058"; }
  i.icon.check.square.outline:before { content: "\f14a"; }
  i.icon.circle.outline:before { content: "\f111"; }
  i.icon.clipboard.outline:before { content: "\f328"; }
  i.icon.clock.outline:before { content: "\f017"; }
  i.icon.clone.outline:before { content: "\f24d"; }
  i.icon.closed.captioning.outline:before { content: "\f20a"; }
  i.icon.comment.alternate.outline:before { content: "\f27a"; }
  i.icon.comment.dots.outline:before { content: "\f4ad"; }
  i.icon.comment.outline:before { content: "\f075"; }
  i.icon.comments.outline:before { content: "\f086"; }
  i.icon.compass.outline:before { content: "\f14e"; }
  i.icon.copy.outline:before { content: "\f0c5"; }
  i.icon.copyright.outline:before { content: "\f1f9"; }
  i.icon.credit.card.outline:before { content: "\f09d"; }
  i.icon.dizzy.outline:before { content: "\f567"; }
  i.icon.dot.circle.outline:before { content: "\f192"; }
  i.icon.edit.outline:before { content: "\f044"; }
  i.icon.envelope.open.outline:before { content: "\f2b6"; }
  i.icon.envelope.outline:before { content: "\f0e0"; }
  i.icon.eye.outline:before { content: "\f06e"; }
  i.icon.eye.slash.outline:before { content: "\f070"; }
  i.icon.file.alternate.outline:before { content: "\f15c"; }
  i.icon.file.archive.outline:before { content: "\f1c6"; }
  i.icon.file.audio.outline:before { content: "\f1c7"; }
  i.icon.file.code.outline:before { content: "\f1c9"; }
  i.icon.file.excel.outline:before { content: "\f1c3"; }
  i.icon.file.image.outline:before { content: "\f1c5"; }
  i.icon.file.outline:before { content: "\f15b"; }
  i.icon.file.pdf.outline:before { content: "\f1c1"; }
  i.icon.file.powerpoint.outline:before { content: "\f1c4"; }
  i.icon.file.video.outline:before { content: "\f1c8"; }
  i.icon.file.word.outline:before { content: "\f1c2"; }
  i.icon.flag.outline:before { content: "\f024"; }
  i.icon.flushed.outline:before { content: "\f579"; }
  i.icon.folder.open.outline:before { content: "\f07c"; }
  i.icon.folder.outline:before { content: "\f07b"; }
  i.icon.frown.open.outline:before { content: "\f57a"; }
  i.icon.frown.outline:before { content: "\f119"; }
  i.icon.futbol.outline:before { content: "\f1e3"; }
  i.icon.gem.outline:before { content: "\f3a5"; }
  i.icon.grimace.outline:before { content: "\f57f"; }
  i.icon.grin.alternate.outline:before { content: "\f581"; }
  i.icon.grin.beam.outline:before { content: "\f582"; }
  i.icon.grin.beam.sweat.outline:before { content: "\f583"; }
  i.icon.grin.hearts.outline:before { content: "\f584"; }
  i.icon.grin.outline:before { content: "\f580"; }
  i.icon.grin.squint.outline:before { content: "\f585"; }
  i.icon.grin.squint.tears.outline:before { content: "\f586"; }
  i.icon.grin.stars.outline:before { content: "\f587"; }
  i.icon.grin.tears.outline:before { content: "\f588"; }
  i.icon.grin.tongue.outline:before { content: "\f589"; }
  i.icon.grin.tongue.squint.outline:before { content: "\f58a"; }
  i.icon.grin.tongue.wink.outline:before { content: "\f58b"; }
  i.icon.grin.wink.outline:before { content: "\f58c"; }
  i.icon.hand.lizard.outline:before { content: "\f258"; }
  i.icon.hand.paper.outline:before { content: "\f256"; }
  i.icon.hand.peace.outline:before { content: "\f25b"; }
  i.icon.hand.point.down.outline:before { content: "\f0a7"; }
  i.icon.hand.point.left.outline:before { content: "\f0a5"; }
  i.icon.hand.point.right.outline:before { content: "\f0a4"; }
  i.icon.hand.point.up.outline:before { content: "\f0a6"; }
  i.icon.hand.pointer.outline:before { content: "\f25a"; }
  i.icon.hand.rock.outline:before { content: "\f255"; }
  i.icon.hand.scissors.outline:before { content: "\f257"; }
  i.icon.hand.spock.outline:before { content: "\f259"; }
  i.icon.handshake.outline:before { content: "\f2b5"; }
  i.icon.hdd.outline:before { content: "\f0a0"; }
  i.icon.heart.outline:before { content: "\f004"; }
  i.icon.hospital.outline:before { content: "\f0f8"; }
  i.icon.hourglass.outline:before { content: "\f254"; }
  i.icon.id.badge.outline:before { content: "\f2c1"; }
  i.icon.id.card.outline:before { content: "\f2c2"; }
  i.icon.image.outline:before { content: "\f03e"; }
  i.icon.images.outline:before { content: "\f302"; }
  i.icon.keyboard.outline:before { content: "\f11c"; }
  i.icon.kiss.beam.outline:before { content: "\f597"; }
  i.icon.kiss.outline:before { content: "\f596"; }
  i.icon.kiss.wink.heart.outline:before { content: "\f598"; }
  i.icon.laugh.beam.outline:before { content: "\f59a"; }
  i.icon.laugh.outline:before { content: "\f599"; }
  i.icon.laugh.squint.outline:before { content: "\f59b"; }
  i.icon.laugh.wink.outline:before { content: "\f59c"; }
  i.icon.lemon.outline:before { content: "\f094"; }
  i.icon.life.ring.outline:before { content: "\f1cd"; }
  i.icon.lightbulb.outline:before { content: "\f0eb"; }
  i.icon.list.alternate.outline:before { content: "\f022"; }
  i.icon.map.outline:before { content: "\f279"; }
  i.icon.meh.blank.outline:before { content: "\f5a4"; }
  i.icon.meh.outline:before { content: "\f11a"; }
  i.icon.meh.rolling.eyes.outline:before { content: "\f5a5"; }
  i.icon.minus.square.outline:before { content: "\f146"; }
  i.icon.money.bill.alternate.outline:before { content: "\f3d1"; }
  i.icon.moon.outline:before { content: "\f186"; }
  i.icon.newspaper.outline:before { content: "\f1ea"; }
  i.icon.object.group.outline:before { content: "\f247"; }
  i.icon.object.ungroup.outline:before { content: "\f248"; }
  i.icon.paper.plane.outline:before { content: "\f1d8"; }
  i.icon.pause.circle.outline:before { content: "\f28b"; }
  i.icon.play.circle.outline:before { content: "\f144"; }
  i.icon.plus.square.outline:before { content: "\f0fe"; }
  i.icon.question.circle.outline:before { content: "\f059"; }
  i.icon.registered.outline:before { content: "\f25d"; }
  i.icon.sad.cry.outline:before { content: "\f5b3"; }
  i.icon.sad.tear.outline:before { content: "\f5b4"; }
  i.icon.save.outline:before { content: "\f0c7"; }
  i.icon.share.square.outline:before { content: "\f14d"; }
  i.icon.smile.beam.outline:before { content: "\f5b8"; }
  i.icon.smile.outline:before { content: "\f118"; }
  i.icon.smile.wink.outline:before { content: "\f4da"; }
  i.icon.snowflake.outline:before { content: "\f2dc"; }
  i.icon.square.outline:before { content: "\f0c8"; }
  i.icon.star.half.outline:before { content: "\f089"; }
  i.icon.star.outline:before { content: "\f005"; }
  i.icon.sticky.note.outline:before { content: "\f249"; }
  i.icon.stop.circle.outline:before { content: "\f28d"; }
  i.icon.sun.outline:before { content: "\f185"; }
  i.icon.surprise.outline:before { content: "\f5c2"; }
  i.icon.thumbs.down.outline:before { content: "\f165"; }
  i.icon.thumbs.up.outline:before { content: "\f164"; }
  i.icon.times.circle.outline:before { content: "\f057"; }
  i.icon.tired.outline:before { content: "\f5c8"; }
  i.icon.trash.alternate.outline:before { content: "\f2ed"; }
  i.icon.user.circle.outline:before { content: "\f2bd"; }
  i.icon.user.outline:before { content: "\f007"; }
  i.icon.window.close.outline:before { content: "\f410"; }
  i.icon.window.maximize.outline:before { content: "\f2d0"; }
  i.icon.window.minimize.outline:before { content: "\f2d1"; }
  i.icon.window.restore.outline:before { content: "\f2d2"; }



}
.loadOutlineIcons();



/*******************************
          Brand Icons
*******************************/

.loadBrandIcons() when (@importBrandIcons) {
  /* Load & Define Brand Font */
  /*
  @font-face {
    font-family: @brandFontName;
    src: @brandFallbackSRC;
    src: @brandSrc;
    font-style: normal;
    font-weight: @normal;
    font-variant: normal;
    text-decoration: inherit;
    text-transform: none;
  }
  */

  /* Icons */
  i.icon.\35 00px:before { content: "\f26e"; font-family: @brandFontName; }
  i.icon.accessible:before { content: "\f368"; font-family: @brandFontName; }
  i.icon.accusoft:before { content: "\f369"; font-family: @brandFontName; }
  i.icon.acquisitions.incorporated:before { content: "\f6af"; font-family: @brandFontName; }
  i.icon.adn:before { content: "\f170"; font-family: @brandFontName; }
  i.icon.adobe:before { content: "\f778"; font-family: @brandFontName; }
  i.icon.adversal:before { content: "\f36a"; font-family: @brandFontName; }
  i.icon.affiliatetheme:before { content: "\f36b"; font-family: @brandFontName; }
  i.icon.airbnb:before { content: "\f834"; font-family: @brandFontName; }
  i.icon.algolia:before { content: "\f36c"; font-family: @brandFontName; }
  i.icon.alipay:before { content: "\f642"; font-family: @brandFontName; }
  i.icon.amazon:before { content: "\f270"; font-family: @brandFontName; }
  i.icon.amazon.pay:before { content: "\f42c"; font-family: @brandFontName; }
  i.icon.amilia:before { content: "\f36d"; font-family: @brandFontName; }
  i.icon.android:before { content: "\f17b"; font-family: @brandFontName; }
  i.icon.angellist:before { content: "\f209"; font-family: @brandFontName; }
  i.icon.angrycreative:before { content: "\f36e"; font-family: @brandFontName; }
  i.icon.angular:before { content: "\f420"; font-family: @brandFontName; }
  i.icon.app.store:before { content: "\f36f"; font-family: @brandFontName; }
  i.icon.app.store.ios:before { content: "\f370"; font-family: @brandFontName; }
  i.icon.apper:before { content: "\f371"; font-family: @brandFontName; }
  i.icon.apple:before { content: "\f179"; font-family: @brandFontName; }
  i.icon.apple.pay:before { content: "\f415"; font-family: @brandFontName; }
  i.icon.artstation:before { content: "\f77a"; font-family: @brandFontName; }
  i.icon.asymmetrik:before { content: "\f372"; font-family: @brandFontName; }
  i.icon.atlassian:before { content: "\f77b"; font-family: @brandFontName; }
  i.icon.audible:before { content: "\f373"; font-family: @brandFontName; }
  i.icon.autoprefixer:before { content: "\f41c"; font-family: @brandFontName; }
  i.icon.avianex:before { content: "\f374"; font-family: @brandFontName; }
  i.icon.aviato:before { content: "\f421"; font-family: @brandFontName; }
  i.icon.aws:before { content: "\f375"; font-family: @brandFontName; }
  i.icon.bandcamp:before { content: "\f2d5"; font-family: @brandFontName; }
  i.icon.battle.net:before { content: "\f835"; font-family: @brandFontName; }
  i.icon.behance:before { content: "\f1b4"; font-family: @brandFontName; }
  i.icon.behance.square:before { content: "\f1b5"; font-family: @brandFontName; }
  i.icon.bimobject:before { content: "\f378"; font-family: @brandFontName; }
  i.icon.bitbucket:before { content: "\f171"; font-family: @brandFontName; }
  i.icon.bitcoin:before { content: "\f379"; font-family: @brandFontName; }
  i.icon.bity:before { content: "\f37a"; font-family: @brandFontName; }
  i.icon.black.tie:before { content: "\f27e"; font-family: @brandFontName; }
  i.icon.blackberry:before { content: "\f37b"; font-family: @brandFontName; }
  i.icon.blogger:before { content: "\f37c"; font-family: @brandFontName; }
  i.icon.blogger.b:before { content: "\f37d"; font-family: @brandFontName; }
  i.icon.bluetooth:before { content: "\f293"; font-family: @brandFontName; }
  i.icon.bluetooth.b:before { content: "\f294"; font-family: @brandFontName; }
  i.icon.bootstrap:before { content: "\f836"; font-family: @brandFontName; }
  i.icon.btc:before { content: "\f15a"; font-family: @brandFontName; }
  i.icon.buffer:before { content: "\f837"; font-family: @brandFontName; }
  i.icon.buromobelexperte:before { content: "\f37f"; font-family: @brandFontName; }
  i.icon.buy.n.large:before { content: "\f8a6"; font-family: @brandFontName; }
  i.icon.buysellads:before { content: "\f20d"; font-family: @brandFontName; }
  i.icon.canadian.maple.leaf:before { content: "\f785"; font-family: @brandFontName; }
  i.icon.cc.amazon.pay:before { content: "\f42d"; font-family: @brandFontName; }
  i.icon.cc.amex:before { content: "\f1f3"; font-family: @brandFontName; }
  i.icon.cc.apple.pay:before { content: "\f416"; font-family: @brandFontName; }
  i.icon.cc.diners.club:before { content: "\f24c"; font-family: @brandFontName; }
  i.icon.cc.discover:before { content: "\f1f2"; font-family: @brandFontName; }
  i.icon.cc.jcb:before { content: "\f24b"; font-family: @brandFontName; }
  i.icon.cc.mastercard:before { content: "\f1f1"; font-family: @brandFontName; }
  i.icon.cc.paypal:before { content: "\f1f4"; font-family: @brandFontName; }
  i.icon.cc.stripe:before { content: "\f1f5"; font-family: @brandFontName; }
  i.icon.cc.visa:before { content: "\f1f0"; font-family: @brandFontName; }
  i.icon.centercode:before { content: "\f380"; font-family: @brandFontName; }
  i.icon.centos:before { content: "\f789"; font-family: @brandFontName; }
  i.icon.chrome:before { content: "\f268"; font-family: @brandFontName; }
  i.icon.chromecast:before { content: "\f838"; font-family: @brandFontName; }
  i.icon.cloudscale:before { content: "\f383"; font-family: @brandFontName; }
  i.icon.cloudsmith:before { content: "\f384"; font-family: @brandFontName; }
  i.icon.cloudversify:before { content: "\f385"; font-family: @brandFontName; }
  i.icon.codepen:before { content: "\f1cb"; font-family: @brandFontName; }
  i.icon.codiepie:before { content: "\f284"; font-family: @brandFontName; }
  i.icon.confluence:before { content: "\f78d"; font-family: @brandFontName; }
  i.icon.connectdevelop:before { content: "\f20e"; font-family: @brandFontName; }
  i.icon.contao:before { content: "\f26d"; font-family: @brandFontName; }
  i.icon.cotton.bureau:before { content: "\f89e"; font-family: @brandFontName; }
  i.icon.cpanel:before { content: "\f388"; font-family: @brandFontName; }
  i.icon.creative.commons:before { content: "\f25e"; font-family: @brandFontName; }
  i.icon.creative.commons.by:before { content: "\f4e7"; font-family: @brandFontName; }
  i.icon.creative.commons.nc:before { content: "\f4e8"; font-family: @brandFontName; }
  i.icon.creative.commons.nc.eu:before { content: "\f4e9"; font-family: @brandFontName; }
  i.icon.creative.commons.nc.jp:before { content: "\f4ea"; font-family: @brandFontName; }
  i.icon.creative.commons.nd:before { content: "\f4eb"; font-family: @brandFontName; }
  i.icon.creative.commons.pd:before { content: "\f4ec"; font-family: @brandFontName; }
  i.icon.creative.commons.pd.alternate:before { content: "\f4ed"; font-family: @brandFontName; }
  i.icon.creative.commons.remix:before { content: "\f4ee"; font-family: @brandFontName; }
  i.icon.creative.commons.sa:before { content: "\f4ef"; font-family: @brandFontName; }
  i.icon.creative.commons.sampling:before { content: "\f4f0"; font-family: @brandFontName; }
  i.icon.creative.commons.sampling.plus:before { content: "\f4f1"; font-family: @brandFontName; }
  i.icon.creative.commons.share:before { content: "\f4f2"; font-family: @brandFontName; }
  i.icon.creative.commons.zero:before { content: "\f4f3"; font-family: @brandFontName; }
  i.icon.critical.role:before { content: "\f6c9"; font-family: @brandFontName; }
  i.icon.css3:before { content: "\f13c"; font-family: @brandFontName; }
  i.icon.css3.alternate:before { content: "\f38b"; font-family: @brandFontName; }
  i.icon.cuttlefish:before { content: "\f38c"; font-family: @brandFontName; }
  i.icon.d.and.d:before { content: "\f38d"; font-family: @brandFontName; }
  i.icon.d.and.d.beyond:before { content: "\f6ca"; font-family: @brandFontName; }
  i.icon.dailymotion:before { content: "\f952"; font-family: @brandFontName; }
  i.icon.dashcube:before { content: "\f210"; font-family: @brandFontName; }
  i.icon.delicious:before { content: "\f1a5"; font-family: @brandFontName; }
  i.icon.deploydog:before { content: "\f38e"; font-family: @brandFontName; }
  i.icon.deskpro:before { content: "\f38f"; font-family: @brandFontName; }
  i.icon.dev:before { content: "\f6cc"; font-family: @brandFontName; }
  i.icon.deviantart:before { content: "\f1bd"; font-family: @brandFontName; }
  i.icon.dhl:before { content: "\f790"; font-family: @brandFontName; }
  i.icon.diaspora:before { content: "\f791"; font-family: @brandFontName; }
  i.icon.digg:before { content: "\f1a6"; font-family: @brandFontName; }
  i.icon.digital.ocean:before { content: "\f391"; font-family: @brandFontName; }
  i.icon.discord:before { content: "\f392"; font-family: @brandFontName; }
  i.icon.discourse:before { content: "\f393"; font-family: @brandFontName; }
  i.icon.dochub:before { content: "\f394"; font-family: @brandFontName; }
  i.icon.docker:before { content: "\f395"; font-family: @brandFontName; }
  i.icon.draft2digital:before { content: "\f396"; font-family: @brandFontName; }
  i.icon.dribbble:before { content: "\f17d"; font-family: @brandFontName; }
  i.icon.dribbble.square:before { content: "\f397"; font-family: @brandFontName; }
  i.icon.dropbox:before { content: "\f16b"; font-family: @brandFontName; }
  i.icon.drupal:before { content: "\f1a9"; font-family: @brandFontName; }
  i.icon.dyalog:before { content: "\f399"; font-family: @brandFontName; }
  i.icon.earlybirds:before { content: "\f39a"; font-family: @brandFontName; }
  i.icon.ebay:before { content: "\f4f4"; font-family: @brandFontName; }
  i.icon.edge:before { content: "\f282"; font-family: @brandFontName; }
  i.icon.elementor:before { content: "\f430"; font-family: @brandFontName; }
  i.icon.ello:before { content: "\f5f1"; font-family: @brandFontName; }
  i.icon.ember:before { content: "\f423"; font-family: @brandFontName; }
  i.icon.empire:before { content: "\f1d1"; font-family: @brandFontName; }
  i.icon.envira:before { content: "\f299"; font-family: @brandFontName; }
  i.icon.erlang:before { content: "\f39d"; font-family: @brandFontName; }
  i.icon.ethereum:before { content: "\f42e"; font-family: @brandFontName; }
  i.icon.etsy:before { content: "\f2d7"; font-family: @brandFontName; }
  i.icon.evernote:before { content: "\f839"; font-family: @brandFontName; }
  i.icon.expeditedssl:before { content: "\f23e"; font-family: @brandFontName; }
  i.icon.facebook:before { content: "\f09a"; font-family: @brandFontName; }
  i.icon.facebook.f:before { content: "\f39e"; font-family: @brandFontName; }
  i.icon.facebook.messenger:before { content: "\f39f"; font-family: @brandFontName; }
  i.icon.facebook.square:before { content: "\f082"; font-family: @brandFontName; }
  i.icon.fantasy.flight.games:before { content: "\f6dc"; font-family: @brandFontName; }
  i.icon.fedex:before { content: "\f797"; font-family: @brandFontName; }
  i.icon.fedora:before { content: "\f798"; font-family: @brandFontName; }
  i.icon.figma:before { content: "\f799"; font-family: @brandFontName; }
  i.icon.firefox:before { content: "\f269"; font-family: @brandFontName; }
  i.icon.firefox.browser:before { content: "\f907"; font-family: @brandFontName; }
  i.icon.first.order:before { content: "\f2b0"; font-family: @brandFontName; }
  i.icon.first.order.alternate:before { content: "\f50a"; font-family: @brandFontName; }
  i.icon.firstdraft:before { content: "\f3a1"; font-family: @brandFontName; }
  i.icon.flickr:before { content: "\f16e"; font-family: @brandFontName; }
  i.icon.flipboard:before { content: "\f44d"; font-family: @brandFontName; }
  i.icon.fly:before { content: "\f417"; font-family: @brandFontName; }
  i.icon.font.awesome:before { content: "\f2b4"; font-family: @brandFontName; }
  i.icon.font.awesome.alternate:before { content: "\f35c"; font-family: @brandFontName; }
  i.icon.font.awesome.flag:before { content: "\f425"; font-family: @brandFontName; }
  i.icon.fonticons:before { content: "\f280"; font-family: @brandFontName; }
  i.icon.fonticons.fi:before { content: "\f3a2"; font-family: @brandFontName; }
  i.icon.fort.awesome:before { content: "\f286"; font-family: @brandFontName; }
  i.icon.fort.awesome.alternate:before { content: "\f3a3"; font-family: @brandFontName; }
  i.icon.forumbee:before { content: "\f211"; font-family: @brandFontName; }
  i.icon.foursquare:before { content: "\f180"; font-family: @brandFontName; }
  i.icon.free.code.camp:before { content: "\f2c5"; font-family: @brandFontName; }
  i.icon.freebsd:before { content: "\f3a4"; font-family: @brandFontName; }
  i.icon.fulcrum:before { content: "\f50b"; font-family: @brandFontName; }
  i.icon.galactic.republic:before { content: "\f50c"; font-family: @brandFontName; }
  i.icon.galactic.senate:before { content: "\f50d"; font-family: @brandFontName; }
  i.icon.get.pocket:before { content: "\f265"; font-family: @brandFontName; }
  i.icon.gg:before { content: "\f260"; font-family: @brandFontName; }
  i.icon.gg.circle:before { content: "\f261"; font-family: @brandFontName; }
  i.icon.git:before { content: "\f1d3"; font-family: @brandFontName; }
  i.icon.git.alternate:before { content: "\f841"; font-family: @brandFontName; }
  i.icon.git.square:before { content: "\f1d2"; font-family: @brandFontName; }
  i.icon.github:before { content: "\f09b"; font-family: @brandFontName; }
  i.icon.github.alternate:before { content: "\f113"; font-family: @brandFontName; }
  i.icon.github.square:before { content: "\f092"; font-family: @brandFontName; }
  i.icon.gitkraken:before { content: "\f3a6"; font-family: @brandFontName; }
  i.icon.gitlab:before { content: "\f296"; font-family: @brandFontName; }
  i.icon.gitter:before { content: "\f426"; font-family: @brandFontName; }
  i.icon.glide:before { content: "\f2a5"; font-family: @brandFontName; }
  i.icon.glide.g:before { content: "\f2a6"; font-family: @brandFontName; }
  i.icon.gofore:before { content: "\f3a7"; font-family: @brandFontName; }
  i.icon.goodreads:before { content: "\f3a8"; font-family: @brandFontName; }
  i.icon.goodreads.g:before { content: "\f3a9"; font-family: @brandFontName; }
  i.icon.google:before { content: "\f1a0"; font-family: @brandFontName; }
  i.icon.google.drive:before { content: "\f3aa"; font-family: @brandFontName; }
  i.icon.google.play:before { content: "\f3ab"; font-family: @brandFontName; }
  i.icon.google.plus:before { content: "\f2b3"; font-family: @brandFontName; }
  i.icon.google.plus.g:before { content: "\f0d5"; font-family: @brandFontName; }
  i.icon.google.plus.square:before { content: "\f0d4"; font-family: @brandFontName; }
  i.icon.google.wallet:before { content: "\f1ee"; font-family: @brandFontName; }
  i.icon.gratipay:before { content: "\f184"; font-family: @brandFontName; }
  i.icon.grav:before { content: "\f2d6"; font-family: @brandFontName; }
  i.icon.gripfire:before { content: "\f3ac"; font-family: @brandFontName; }
  i.icon.grunt:before { content: "\f3ad"; font-family: @brandFontName; }
  i.icon.gulp:before { content: "\f3ae"; font-family: @brandFontName; }
  i.icon.hacker.news:before { content: "\f1d4"; font-family: @brandFontName; }
  i.icon.hacker.news.square:before { content: "\f3af"; font-family: @brandFontName; }
  i.icon.hackerrank:before { content: "\f5f7"; font-family: @brandFontName; }
  i.icon.hips:before { content: "\f452"; font-family: @brandFontName; }
  i.icon.hire.a.helper:before { content: "\f3b0"; font-family: @brandFontName; }
  i.icon.hooli:before { content: "\f427"; font-family: @brandFontName; }
  i.icon.hornbill:before { content: "\f592"; font-family: @brandFontName; }
  i.icon.hotjar:before { content: "\f3b1"; font-family: @brandFontName; }
  i.icon.houzz:before { content: "\f27c"; font-family: @brandFontName; }
  i.icon.html5:before { content: "\f13b"; font-family: @brandFontName; }
  i.icon.hubspot:before { content: "\f3b2"; font-family: @brandFontName; }
  i.icon.ideal:before { content: "\f913"; font-family: @brandFontName; }
  i.icon.imdb:before { content: "\f2d8"; font-family: @brandFontName; }
  i.icon.instagram:before { content: "\f16d"; font-family: @brandFontName; }
  i.icon.instagram.square:before { content: "\f955"; font-family: @brandFontName; }
  i.icon.intercom:before { content: "\f7af"; font-family: @brandFontName; }
  i.icon.internet.explorer:before { content: "\f26b"; font-family: @brandFontName; }
  i.icon.invision:before { content: "\f7b0"; font-family: @brandFontName; }
  i.icon.ioxhost:before { content: "\f208"; font-family: @brandFontName; }
  i.icon.itch.io:before { content: "\f83a"; font-family: @brandFontName; }
  i.icon.itunes:before { content: "\f3b4"; font-family: @brandFontName; }
  i.icon.itunes.note:before { content: "\f3b5"; font-family: @brandFontName; }
  i.icon.java:before { content: "\f4e4"; font-family: @brandFontName; }
  i.icon.jedi.order:before { content: "\f50e"; font-family: @brandFontName; }
  i.icon.jenkins:before { content: "\f3b6"; font-family: @brandFontName; }
  i.icon.jira:before { content: "\f7b1"; font-family: @brandFontName; }
  i.icon.joget:before { content: "\f3b7"; font-family: @brandFontName; }
  i.icon.joomla:before { content: "\f1aa"; font-family: @brandFontName; }
  i.icon.js:before { content: "\f3b8"; font-family: @brandFontName; }
  i.icon.js.square:before { content: "\f3b9"; font-family: @brandFontName; }
  i.icon.jsfiddle:before { content: "\f1cc"; font-family: @brandFontName; }
  i.icon.kaggle:before { content: "\f5fa"; font-family: @brandFontName; }
  i.icon.keybase:before { content: "\f4f5"; font-family: @brandFontName; }
  i.icon.keycdn:before { content: "\f3ba"; font-family: @brandFontName; }
  i.icon.kickstarter:before { content: "\f3bb"; font-family: @brandFontName; }
  i.icon.kickstarter.k:before { content: "\f3bc"; font-family: @brandFontName; }
  i.icon.korvue:before { content: "\f42f"; font-family: @brandFontName; }
  i.icon.laravel:before { content: "\f3bd"; font-family: @brandFontName; }
  i.icon.lastfm:before { content: "\f202"; font-family: @brandFontName; }
  i.icon.lastfm.square:before { content: "\f203"; font-family: @brandFontName; }
  i.icon.leanpub:before { content: "\f212"; font-family: @brandFontName; }
  i.icon.lesscss:before { content: "\f41d"; font-family: @brandFontName; }
  i.icon.linechat:before { content: "\f3c0"; font-family: @brandFontName; }
  i.icon.linkedin:before { content: "\f08c"; font-family: @brandFontName; }
  i.icon.linkedin.in:before { content: "\f0e1"; font-family: @brandFontName; }
  i.icon.linode:before { content: "\f2b8"; font-family: @brandFontName; }
  i.icon.linux:before { content: "\f17c"; font-family: @brandFontName; }
  i.icon.lyft:before { content: "\f3c3"; font-family: @brandFontName; }
  i.icon.magento:before { content: "\f3c4"; font-family: @brandFontName; }
  i.icon.mailchimp:before { content: "\f59e"; font-family: @brandFontName; }
  i.icon.mandalorian:before { content: "\f50f"; font-family: @brandFontName; }
  i.icon.markdown:before { content: "\f60f"; font-family: @brandFontName; }
  i.icon.mastodon:before { content: "\f4f6"; font-family: @brandFontName; }
  i.icon.maxcdn:before { content: "\f136"; font-family: @brandFontName; }
  i.icon.mdb:before { content: "\f8ca"; font-family: @brandFontName; }
  i.icon.medapps:before { content: "\f3c6"; font-family: @brandFontName; }
  i.icon.medium:before { content: "\f23a"; font-family: @brandFontName; }
  i.icon.medium.m:before { content: "\f3c7"; font-family: @brandFontName; }
  i.icon.medrt:before { content: "\f3c8"; font-family: @brandFontName; }
  i.icon.meetup:before { content: "\f2e0"; font-family: @brandFontName; }
  i.icon.megaport:before { content: "\f5a3"; font-family: @brandFontName; }
  i.icon.mendeley:before { content: "\f7b3"; font-family: @brandFontName; }
  i.icon.microblog:before { content: "\f91a"; font-family: @brandFontName; }
  i.icon.microsoft:before { content: "\f3ca"; font-family: @brandFontName; }
  i.icon.mix:before { content: "\f3cb"; font-family: @brandFontName; }
  i.icon.mixcloud:before { content: "\f289"; font-family: @brandFontName; }
  i.icon.mixer:before { content: "\f956"; font-family: @brandFontName; }
  i.icon.mizuni:before { content: "\f3cc"; font-family: @brandFontName; }
  i.icon.modx:before { content: "\f285"; font-family: @brandFontName; }
  i.icon.monero:before { content: "\f3d0"; font-family: @brandFontName; }
  i.icon.napster:before { content: "\f3d2"; font-family: @brandFontName; }
  i.icon.neos:before { content: "\f612"; font-family: @brandFontName; }
  i.icon.nimblr:before { content: "\f5a8"; font-family: @brandFontName; }
  i.icon.node:before { content: "\f419"; font-family: @brandFontName; }
  i.icon.node.js:before { content: "\f3d3"; font-family: @brandFontName; }
  i.icon.npm:before { content: "\f3d4"; font-family: @brandFontName; }
  i.icon.ns8:before { content: "\f3d5"; font-family: @brandFontName; }
  i.icon.nutritionix:before { content: "\f3d6"; font-family: @brandFontName; }
  i.icon.odnoklassniki:before { content: "\f263"; font-family: @brandFontName; }
  i.icon.odnoklassniki.square:before { content: "\f264"; font-family: @brandFontName; }
  i.icon.old.republic:before { content: "\f510"; font-family: @brandFontName; }
  i.icon.opencart:before { content: "\f23d"; font-family: @brandFontName; }
  i.icon.openid:before { content: "\f19b"; font-family: @brandFontName; }
  i.icon.opera:before { content: "\f26a"; font-family: @brandFontName; }
  i.icon.optin.monster:before { content: "\f23c"; font-family: @brandFontName; }
  i.icon.orcid:before { content: "\f8d2"; font-family: @brandFontName; }
  i.icon.osi:before { content: "\f41a"; font-family: @brandFontName; }
  i.icon.page4:before { content: "\f3d7"; font-family: @brandFontName; }
  i.icon.pagelines:before { content: "\f18c"; font-family: @brandFontName; }
  i.icon.palfed:before { content: "\f3d8"; font-family: @brandFontName; }
  i.icon.patreon:before { content: "\f3d9"; font-family: @brandFontName; }
  i.icon.paypal:before { content: "\f1ed"; font-family: @brandFontName; }
  i.icon.penny.arcade:before { content: "\f704"; font-family: @brandFontName; }
  i.icon.periscope:before { content: "\f3da"; font-family: @brandFontName; }
  i.icon.phabricator:before { content: "\f3db"; font-family: @brandFontName; }
  i.icon.phoenix.framework:before { content: "\f3dc"; font-family: @brandFontName; }
  i.icon.phoenix.squadron:before { content: "\f511"; font-family: @brandFontName; }
  i.icon.php:before { content: "\f457"; font-family: @brandFontName; }
  i.icon.pied.piper:before { content: "\f2ae"; font-family: @brandFontName; }
  i.icon.pied.piper.alternate:before { content: "\f1a8"; font-family: @brandFontName; }
  i.icon.pied.piper.hat:before { content: "\f4e5"; font-family: @brandFontName; }
  i.icon.pied.piper.pp:before { content: "\f1a7"; font-family: @brandFontName; }
  i.icon.pied.piper.square:before { content: "\f91e"; font-family: @brandFontName; }
  i.icon.pinterest:before { content: "\f0d2"; font-family: @brandFontName; }
  i.icon.pinterest.p:before { content: "\f231"; font-family: @brandFontName; }
  i.icon.pinterest.square:before { content: "\f0d3"; font-family: @brandFontName; }
  i.icon.playstation:before { content: "\f3df"; font-family: @brandFontName; }
  i.icon.product.hunt:before { content: "\f288"; font-family: @brandFontName; }
  i.icon.pushed:before { content: "\f3e1"; font-family: @brandFontName; }
  i.icon.python:before { content: "\f3e2"; font-family: @brandFontName; }
  i.icon.qq:before { content: "\f1d6"; font-family: @brandFontName; }
  i.icon.quinscape:before { content: "\f459"; font-family: @brandFontName; }
  i.icon.quora:before { content: "\f2c4"; font-family: @brandFontName; }
  i.icon.r.project:before { content: "\f4f7"; font-family: @brandFontName; }
  i.icon.raspberry.pi:before { content: "\f7bb"; font-family: @brandFontName; }
  i.icon.ravelry:before { content: "\f2d9"; font-family: @brandFontName; }
  i.icon.react:before { content: "\f41b"; font-family: @brandFontName; }
  i.icon.reacteurope:before { content: "\f75d"; font-family: @brandFontName; }
  i.icon.readme:before { content: "\f4d5"; font-family: @brandFontName; }
  i.icon.rebel:before { content: "\f1d0"; font-family: @brandFontName; }
  i.icon.reddit:before { content: "\f1a1"; font-family: @brandFontName; }
  i.icon.reddit.alien:before { content: "\f281"; font-family: @brandFontName; }
  i.icon.reddit.square:before { content: "\f1a2"; font-family: @brandFontName; }
  i.icon.redhat:before { content: "\f7bc"; font-family: @brandFontName; }
  i.icon.redriver:before { content: "\f3e3"; font-family: @brandFontName; }
  i.icon.redyeti:before { content: "\f69d"; font-family: @brandFontName; }
  i.icon.renren:before { content: "\f18b"; font-family: @brandFontName; }
  i.icon.replyd:before { content: "\f3e6"; font-family: @brandFontName; }
  i.icon.researchgate:before { content: "\f4f8"; font-family: @brandFontName; }
  i.icon.resolving:before { content: "\f3e7"; font-family: @brandFontName; }
  i.icon.rev:before { content: "\f5b2"; font-family: @brandFontName; }
  i.icon.rocketchat:before { content: "\f3e8"; font-family: @brandFontName; }
  i.icon.rockrms:before { content: "\f3e9"; font-family: @brandFontName; }
  i.icon.safari:before { content: "\f267"; font-family: @brandFontName; }
  i.icon.salesforce:before { content: "\f83b"; font-family: @brandFontName; }
  i.icon.sass:before { content: "\f41e"; font-family: @brandFontName; }
  i.icon.schlix:before { content: "\f3ea"; font-family: @brandFontName; }
  i.icon.scribd:before { content: "\f28a"; font-family: @brandFontName; }
  i.icon.searchengin:before { content: "\f3eb"; font-family: @brandFontName; }
  i.icon.sellcast:before { content: "\f2da"; font-family: @brandFontName; }
  i.icon.sellsy:before { content: "\f213"; font-family: @brandFontName; }
  i.icon.servicestack:before { content: "\f3ec"; font-family: @brandFontName; }
  i.icon.shirtsinbulk:before { content: "\f214"; font-family: @brandFontName; }
  i.icon.shopify:before { content: "\f957"; font-family: @brandFontName; }
  i.icon.shopware:before { content: "\f5b5"; font-family: @brandFontName; }
  i.icon.simplybuilt:before { content: "\f215"; font-family: @brandFontName; }
  i.icon.sistrix:before { content: "\f3ee"; font-family: @brandFontName; }
  i.icon.sith:before { content: "\f512"; font-family: @brandFontName; }
  i.icon.sketch:before { content: "\f7c6"; font-family: @brandFontName; }
  i.icon.skyatlas:before { content: "\f216"; font-family: @brandFontName; }
  i.icon.skype:before { content: "\f17e"; font-family: @brandFontName; }
  i.icon.slack:before { content: "\f198"; font-family: @brandFontName; }
  i.icon.slack.hash:before { content: "\f3ef"; font-family: @brandFontName; }
  i.icon.slideshare:before { content: "\f1e7"; font-family: @brandFontName; }
  i.icon.snapchat:before { content: "\f2ab"; font-family: @brandFontName; }
  i.icon.snapchat.ghost:before { content: "\f2ac"; font-family: @brandFontName; }
  i.icon.snapchat.square:before { content: "\f2ad"; font-family: @brandFontName; }
  i.icon.soundcloud:before { content: "\f1be"; font-family: @brandFontName; }
  i.icon.sourcetree:before { content: "\f7d3"; font-family: @brandFontName; }
  i.icon.speakap:before { content: "\f3f3"; font-family: @brandFontName; }
  i.icon.speaker.deck:before { content: "\f83c"; font-family: @brandFontName; }
  i.icon.spotify:before { content: "\f1bc"; font-family: @brandFontName; }
  i.icon.squarespace:before { content: "\f5be"; font-family: @brandFontName; }
  i.icon.stack.exchange:before { content: "\f18d"; font-family: @brandFontName; }
  i.icon.stack.overflow:before { content: "\f16c"; font-family: @brandFontName; }
  i.icon.stackpath:before { content: "\f842"; font-family: @brandFontName; }
  i.icon.staylinked:before { content: "\f3f5"; font-family: @brandFontName; }
  i.icon.steam:before { content: "\f1b6"; font-family: @brandFontName; }
  i.icon.steam.square:before { content: "\f1b7"; font-family: @brandFontName; }
  i.icon.steam.symbol:before { content: "\f3f6"; font-family: @brandFontName; }
  i.icon.sticker.mule:before { content: "\f3f7"; font-family: @brandFontName; }
  i.icon.strava:before { content: "\f428"; font-family: @brandFontName; }
  i.icon.stripe:before { content: "\f429"; font-family: @brandFontName; }
  i.icon.stripe.s:before { content: "\f42a"; font-family: @brandFontName; }
  i.icon.studiovinari:before { content: "\f3f8"; font-family: @brandFontName; }
  i.icon.stumbleupon:before { content: "\f1a4"; font-family: @brandFontName; }
  i.icon.stumbleupon.circle:before { content: "\f1a3"; font-family: @brandFontName; }
  i.icon.superpowers:before { content: "\f2dd"; font-family: @brandFontName; }
  i.icon.supple:before { content: "\f3f9"; font-family: @brandFontName; }
  i.icon.suse:before { content: "\f7d6"; font-family: @brandFontName; }
  i.icon.swift:before { content: "\f8e1"; font-family: @brandFontName; }
  i.icon.symfony:before { content: "\f83d"; font-family: @brandFontName; }
  i.icon.teamspeak:before { content: "\f4f9"; font-family: @brandFontName; }
  i.icon.telegram:before { content: "\f2c6"; font-family: @brandFontName; }
  i.icon.telegram.plane:before { content: "\f3fe"; font-family: @brandFontName; }
  i.icon.tencent.weibo:before { content: "\f1d5"; font-family: @brandFontName; }
  i.icon.themeco:before { content: "\f5c6"; font-family: @brandFontName; }
  i.icon.themeisle:before { content: "\f2b2"; font-family: @brandFontName; }
  i.icon.think.peaks:before { content: "\f731"; font-family: @brandFontName; }
  i.icon.trade.federation:before { content: "\f513"; font-family: @brandFontName; }
  i.icon.trello:before { content: "\f181"; font-family: @brandFontName; }
  i.icon.tripadvisor:before { content: "\f262"; font-family: @brandFontName; }
  i.icon.tumblr:before { content: "\f173"; font-family: @brandFontName; }
  i.icon.tumblr.square:before { content: "\f174"; font-family: @brandFontName; }
  i.icon.twitch:before { content: "\f1e8"; font-family: @brandFontName; }
  i.icon.twitter:before { content: "\f099"; font-family: @brandFontName; }
  i.icon.twitter.square:before { content: "\f081"; font-family: @brandFontName; }
  i.icon.typo3:before { content: "\f42b"; font-family: @brandFontName; }
  i.icon.uber:before { content: "\f402"; font-family: @brandFontName; }
  i.icon.ubuntu:before { content: "\f7df"; font-family: @brandFontName; }
  i.icon.uikit:before { content: "\f403"; font-family: @brandFontName; }
  i.icon.umbraco:before { content: "\f8e8"; font-family: @brandFontName; }
  i.icon.uniregistry:before { content: "\f404"; font-family: @brandFontName; }
  i.icon.unity:before { content: "\f949"; font-family: @brandFontName; }
  i.icon.untappd:before { content: "\f405"; font-family: @brandFontName; }
  i.icon.ups:before { content: "\f7e0"; font-family: @brandFontName; }
  i.icon.usb:before { content: "\f287"; font-family: @brandFontName; }
  i.icon.usps:before { content: "\f7e1"; font-family: @brandFontName; }
  i.icon.ussunnah:before { content: "\f407"; font-family: @brandFontName; }
  i.icon.vaadin:before { content: "\f408"; font-family: @brandFontName; }
  i.icon.viacoin:before { content: "\f237"; font-family: @brandFontName; }
  i.icon.viadeo:before { content: "\f2a9"; font-family: @brandFontName; }
  i.icon.viadeo.square:before { content: "\f2aa"; font-family: @brandFontName; }
  i.icon.viber:before { content: "\f409"; font-family: @brandFontName; }
  i.icon.vimeo:before { content: "\f40a"; font-family: @brandFontName; }
  i.icon.vimeo.square:before { content: "\f194"; font-family: @brandFontName; }
  i.icon.vimeo.v:before { content: "\f27d"; font-family: @brandFontName; }
  i.icon.vine:before { content: "\f1ca"; font-family: @brandFontName; }
  i.icon.vk:before { content: "\f189"; font-family: @brandFontName; }
  i.icon.vnv:before { content: "\f40b"; font-family: @brandFontName; }
  i.icon.vuejs:before { content: "\f41f"; font-family: @brandFontName; }
  i.icon.waze:before { content: "\f83f"; font-family: @brandFontName; }
  i.icon.weebly:before { content: "\f5cc"; font-family: @brandFontName; }
  i.icon.weibo:before { content: "\f18a"; font-family: @brandFontName; }
  i.icon.weixin:before { content: "\f1d7"; font-family: @brandFontName; }
  i.icon.whatsapp:before { content: "\f232"; font-family: @brandFontName; }
  i.icon.whatsapp.square:before { content: "\f40c"; font-family: @brandFontName; }
  i.icon.whmcs:before { content: "\f40d"; font-family: @brandFontName; }
  i.icon.wikipedia.w:before { content: "\f266"; font-family: @brandFontName; }
  i.icon.windows:before { content: "\f17a"; font-family: @brandFontName; }
  i.icon.wix:before { content: "\f5cf"; font-family: @brandFontName; }
  i.icon.wizards.of.the.coast:before { content: "\f730"; font-family: @brandFontName; }
  i.icon.wolf.pack.battalion:before { content: "\f514"; font-family: @brandFontName; }
  i.icon.wordpress:before { content: "\f19a"; font-family: @brandFontName; }
  i.icon.wordpress.simple:before { content: "\f411"; font-family: @brandFontName; }
  i.icon.wpbeginner:before { content: "\f297"; font-family: @brandFontName; }
  i.icon.wpexplorer:before { content: "\f2de"; font-family: @brandFontName; }
  i.icon.wpforms:before { content: "\f298"; font-family: @brandFontName; }
  i.icon.wpressr:before { content: "\f3e4"; font-family: @brandFontName; }
  i.icon.xbox:before { content: "\f412"; font-family: @brandFontName; }
  i.icon.xing:before { content: "\f168"; font-family: @brandFontName; }
  i.icon.xing.square:before { content: "\f169"; font-family: @brandFontName; }
  i.icon.y.combinator:before { content: "\f23b"; font-family: @brandFontName; }
  i.icon.yahoo:before { content: "\f19e"; font-family: @brandFontName; }
  i.icon.yammer:before { content: "\f840"; font-family: @brandFontName; }
  i.icon.yandex:before { content: "\f413"; font-family: @brandFontName; }
  i.icon.yandex.international:before { content: "\f414"; font-family: @brandFontName; }
  i.icon.yarn:before { content: "\f7e3"; font-family: @brandFontName; }
  i.icon.yelp:before { content: "\f1e9"; font-family: @brandFontName; }
  i.icon.yoast:before { content: "\f2b1"; font-family: @brandFontName; }
  i.icon.youtube:before { content: "\f167"; font-family: @brandFontName; }
  i.icon.youtube.square:before { content: "\f431"; font-family: @brandFontName; }
  i.icon.zhihu:before { content: "\f63f"; font-family: @brandFontName; }


  /* Aliases */
  i.icon.american.express:before { content: "\f1f3"; font-family: @brandFontName; }
  i.icon.american.express.card:before { content: "\f1f3"; font-family: @brandFontName; }
  i.icon.amex:before { content: "\f1f3"; font-family: @brandFontName; }
  i.icon.bitbucket.square:before { content: "\f171"; font-family: @brandFontName; }
  i.icon.bluetooth.alternative:before { content: "\f294"; font-family: @brandFontName; }
  i.icon.credit.card.amazon.pay:before { content: "\f42d"; font-family: @brandFontName; }
  i.icon.credit.card.american.express:before { content: "\f1f3"; font-family: @brandFontName; }
  i.icon.credit.card.diners.club:before { content: "\f24c"; font-family: @brandFontName; }
  i.icon.credit.card.discover:before { content: "\f1f2"; font-family: @brandFontName; }
  i.icon.credit.card.jcb:before { content: "\f24b"; font-family: @brandFontName; }
  i.icon.credit.card.mastercard:before { content: "\f1f1"; font-family: @brandFontName; }
  i.icon.credit.card.paypal:before { content: "\f1f4"; font-family: @brandFontName; }
  i.icon.credit.card.stripe:before { content: "\f1f5"; font-family: @brandFontName; }
  i.icon.credit.card.visa:before { content: "\f1f0"; font-family: @brandFontName; }
  i.icon.diners.club:before { content: "\f24c"; font-family: @brandFontName; }
  i.icon.diners.club.card:before { content: "\f24c"; font-family: @brandFontName; }
  i.icon.discover:before { content: "\f1f2"; font-family: @brandFontName; }
  i.icon.discover.card:before { content: "\f1f2"; font-family: @brandFontName; }
  i.icon.disk.outline:before { content: "\f369"; font-family: @brandFontName; }
  i.icon.dribble:before { content: "\f17d"; font-family: @brandFontName; }
  i.icon.eercast:before { content: "\f2da"; font-family: @brandFontName; }
  i.icon.envira.gallery:before { content: "\f299"; font-family: @brandFontName; }
  i.icon.fa:before { content: "\f2b4"; font-family: @brandFontName; }
  i.icon.facebook.official:before { content: "\f082"; font-family: @brandFontName; }
  i.icon.five.hundred.pixels:before { content: "\f26e"; font-family: @brandFontName; }
  i.icon.gittip:before { content: "\f184"; font-family: @brandFontName; }
  i.icon.google.plus.circle:before { content: "\f2b3"; font-family: @brandFontName; }
  i.icon.google.plus.official:before { content: "\f2b3"; font-family: @brandFontName; }
  i.icon.japan.credit.bureau:before { content: "\f24b"; font-family: @brandFontName; }
  i.icon.japan.credit.bureau.card:before { content: "\f24b"; font-family: @brandFontName; }
  i.icon.jcb:before { content: "\f24b"; font-family: @brandFontName; }
  i.icon.linkedin.square:before { content: "\f08c"; font-family: @brandFontName; }
  i.icon.mastercard:before { content: "\f1f1"; font-family: @brandFontName; }
  i.icon.mastercard.card:before { content: "\f1f1"; font-family: @brandFontName; }
  i.icon.microsoft.edge:before { content: "\f282"; font-family: @brandFontName; }
  i.icon.ms.edge:before { content: "\f282"; font-family: @brandFontName; }
  i.icon.new.pied.piper:before { content: "\f2ae"; font-family: @brandFontName; }
  i.icon.optinmonster:before { content: "\f23c"; font-family: @brandFontName; }
  i.icon.paypal.card:before { content: "\f1f4"; font-family: @brandFontName; }
  i.icon.pied.piper.hat:before { content: "\f2ae"; font-family: @brandFontName; }
  i.icon.pocket:before { content: "\f265"; font-family: @brandFontName; }
  i.icon.stripe.card:before { content: "\f1f5"; font-family: @brandFontName; }
  i.icon.theme.isle:before { content: "\f2b2"; font-family: @brandFontName; }
  i.icon.visa:before { content: "\f1f0"; font-family: @brandFontName; }
  i.icon.visa.card:before { content: "\f1f0"; font-family: @brandFontName; }
  i.icon.wechat:before { content: "\f1d7"; font-family: @brandFontName; }
  i.icon.wikipedia:before { content: "\f266"; font-family: @brandFontName; }
  i.icon.wordpress.beginner:before { content: "\f297"; font-family: @brandFontName; }
  i.icon.wordpress.forms:before { content: "\f298"; font-family: @brandFontName; }
  i.icon.yc:before { content: "\f23b"; font-family: @brandFontName; }
  i.icon.ycombinator:before { content: "\f23b"; font-family: @brandFontName; }
  i.icon.youtube.play:before { content: "\f167"; font-family: @brandFontName; }

}
.loadBrandIcons();
EOT
        );

        @chmod($semantic_icon_override_file, FILE_WRITE_MODE);
    }

//------------------------------------------------------------------------------

}
