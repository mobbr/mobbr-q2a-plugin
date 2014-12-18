mobbr-q2a-plugin
================

Mobbr plugin for Question2Answer (http://www.question2answer.org/)

The plugin makes questions crowdfundable and crowdpayable. Open questions are automatically in crowdfunding mode, once closed the funds are paid to all contributors based on ratio of votes.

To install this plugin (requires Q2A 1.3 or later), create the directory **<q2aroot>/qa-plugin/mobbr-support** and copy all files to this directory. Then open the Plugins section of the Admin panel and check if it is listed. The plugin has its own admin page there.

For questions please go here: http://ask.mobbr.com/plugins where you can see the plugin in action also.

What does the Mobbr plugin do?
------------------------------

The Mobbr plugin inserts the Mobbr metadata in the HTML of the Q2A pages and optionally shows the Mobbr buttons. This makes question/answer threads payable, or donatable. The amount is divided among all participants in the thread and all participants in the community in such a way that everybody who added value, shares in the revenues.
If no answer is selected yet for the thread, the button is in 'pledge' mode ( button is green ), basically enabling crowdfunding of the thread. After an answer is selected or funds a divided among the participants and the button is switched to normal pay mode ( button is blue ).  

The button automatically chooses a different payment calculation depending on the page on which it is used. To pay all community members ( a share of the ad revenues for instance ), use the button on the front-page or members list page. To pay for the answers on the question, use the button on the thread pages.

Your users automatically reveive mail from the Mobbr system, upon registration the money is in their Mobbr wallet. Mobbr is a full featured bankacount / payment system.

See: https://mobbr.com

For more sophisticated applications we have a REST-API available: https://api.mobbr.com
