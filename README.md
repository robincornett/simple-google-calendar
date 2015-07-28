# Simple Google Calendar

This plugin is an attempt to make it easier to add a Google calendar to a website.

## Installation

### Manual

1. Download the latest tagged archive (choose the "zip" option).
2. Unzip the archive.
3. Copy the folder to your `/wp-content/plugins/` directory.
4. Go to the Plugins screen and click __Activate__.

Check out the Codex for more information about [installing plugins manually](http://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation).

### Git

Using git, browse to your `/wp-content/plugins/` directory and clone this repository:

`git clone git@github.com:robincornett/simple-google-calendar.git`

Then go to your Plugins screen and click __Activate__.

## Frequently Asked Questions

### How do I show a calendar on a post or page?

Google calendar is displayed using a shortcode. In its simplest form, the shortcode is just:

`[simplegooglecalendar]`

This will output the US Holiday calendar for the Eastern time zone. You can modify it by adding an `id` parameter, `mode` parameter, and/or a `timezone` parameter. The `id` will look like this:

`id="i_108.174.106.92#sunrise@group.v.calendar.google.com^#A32929"`

Where the first part is the ID of the calendar; the second part (`^#XXXXXX`) changes the color of the calendar. Multiple calendars can be strung together in the `id`, just use commas to separate them. `^` must be used to separate the calendar ID from the color code.

If you leave the `mode` blank, the desktop/month version of the calendar will be output. If you use `mode="agenda"`, then you will have a mobile or list version output. If you use `mode="both"`, then two calendars will be output.

The timezone has to match the input expected by Google. Default is Eastern time, since that's where I live. Central, for example, would look like `America%2FChicago`. Need help figuring out the time zone? Use the [Google Embeddable Calendar Helper](https://www.google.com/calendar/embedhelper?src=en.usa%23holiday%40group.v.calendar.google.com&ctz=America/New_York).

### What colors can I use in the calendar shortcode?

Pink             = #B1365F
Fuchsia          = #5C1158
Red              = #711616
Crimson          = #691426
Orange           = #BE6D00
Orange Red       = #B1440E
Red Orange       = #853104
Burnt Orange     = #8C500B
Brown Orange     = #754916
Gold             = #88880E
Goldenrod        = #AB8B00
Darker Goldenrod = #856508
Pale Green       = #28754E
Lighter Green    = #1B887A
Green            = #28754E
Forest Green     = #0D7813
Olive Green      = #528800
Jungle Green     = #125A12
Another Olive    = #2F6309
Another Green    = #2F6213
Sea Green        = #0F4B38
Golden Olive     = #5F6B02
Green Gray       = #4A716C
Olive Gray       = #6E6E41
Dull Navy        = #29527A
Standard Blue    = #2952A3
Blue Gray        = #4E5D6C
Blue Steel       = #5A6986
Another blue     = #182C57
Dark Blue        = #060D5E
Sea Blue         = #113F47
Violet           = #7A367A
Purple           = #5229A3
Purple Gray      = #865A5A
Purple Brown     = #705770
Deep Purple      = #23164E
Magenta          = #5B123B
Another Purple   = #42104A
Yellow Brown     = #875509
Brown            = #8D6F47
Nice Brown       = #6B3304
Black            = #333333
