<?php if ( file_exists("../booktop.php") ) {
  require_once "../booktop.php";
  ob_start();
}?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta http-equiv="Content-Style-Type" content="text/css" />
  <meta name="generator" content="pandoc" />
  <title></title>
  <style type="text/css">code{white-space: pre;}</style>
</head>
<body>
<h1 id="using-web-services">Using Web Services</h1>
<p>Once it became easy to retrieve documents and parse documents over HTTP using programs, it did not take long to develop an approach where we</p>
<p>started producing documents that were specifically designed to be consumed by other programs (i.e., not HTML to be displayed in a browser).</p>
<p>There are two common formats that we use when exchanging data across the web. The &quot;eXtensible Markup Language&quot; or XML has been in use for a very long time and is best suited for exchanging document-style data. When programs just want to exchange dictionaries, lists, or other internal information with each other, they use JavaScript Object Notation or JSON (see <a href="http://www.json.org">www.json.org</a>). We will look at both formats.</p>
<h2 id="extensible-markup-language---xml">eXtensible Markup Language - XML</h2>
<p>XML looks very similar to HTML, but XML is more structured than HTML. Here is a sample of an XML document:</p>
<pre class="xml"><code>&lt;person&gt;
  &lt;name&gt;Chuck&lt;/name&gt;
  &lt;phone type=&quot;intl&quot;&gt;
     +1 734 303 4456
   &lt;/phone&gt;
   &lt;email hide=&quot;yes&quot;/&gt;
&lt;/person&gt;</code></pre>
<p>Often it is helpful to think of an XML document as a tree structure where there is a top tag <code>person</code> and other tags such as <code>phone</code> are drawn as <em>children</em> of their parent nodes.</p>
<div class="figure">
<img src="../images/xml-tree.svg" alt="A Tree Representation of XML" />
<p class="caption">A Tree Representation of XML</p>
</div>
<h2 id="parsing-xml">Parsing XML</h2>
<p>  </p>
<p>Here is a simple application that parses some XML and extracts some data elements from the XML:</p>
<pre class="python"><code>import xml.etree.ElementTree as ET

data = &#39;&#39;&#39;
&lt;person&gt;
  &lt;name&gt;Chuck&lt;/name&gt;
  &lt;phone type=&quot;intl&quot;&gt;
     +1 734 303 4456
   &lt;/phone&gt;
   &lt;email hide=&quot;yes&quot;/&gt;
&lt;/person&gt;&#39;&#39;&#39;

tree = ET.fromstring(data)
print(&#39;Name:&#39;, tree.find(&#39;name&#39;).text)
print(&#39;Attr:&#39;, tree.find(&#39;email&#39;).get(&#39;hide&#39;))

# Code: http://www.py4e.com/code3/xml1.py</code></pre>
<p>Calling <code>fromstring</code> converts the string representation of the XML into a &quot;tree&quot; of XML nodes. When the XML is in a tree, we have a series of methods we can call to extract portions of data from the XML.</p>
<p>The <code>find</code> function searches through the XML tree and retrieves a <em>node</em> that matches the specified tag. Each node can have some text, some attributes (like hide), and some &quot;child&quot; nodes. Each node can be the top of a tree of nodes.</p>
<pre><code>Name: Chuck
Attr: yes</code></pre>
<p>Using an XML parser such as <code>ElementTree</code> has the advantage that while the XML in this example is quite simple, it turns out there are many rules regarding valid XML and using <code>ElementTree</code> allows us to extract data from XML without worrying about the rules of XML syntax.</p>
<h2 id="looping-through-nodes">Looping through nodes</h2>
<p> </p>
<p>Often the XML has multiple nodes and we need to write a loop to process all of the nodes. In the following program, we loop through all of the <code>user</code> nodes:</p>
<pre class="python"><code>import xml.etree.ElementTree as ET

input = &#39;&#39;&#39;
&lt;stuff&gt;
    &lt;users&gt;
        &lt;user x=&quot;2&quot;&gt;
            &lt;id&gt;001&lt;/id&gt;
            &lt;name&gt;Chuck&lt;/name&gt;
        &lt;/user&gt;
        &lt;user x=&quot;7&quot;&gt;
            &lt;id&gt;009&lt;/id&gt;
            &lt;name&gt;Brent&lt;/name&gt;
        &lt;/user&gt;
    &lt;/users&gt;
&lt;/stuff&gt;&#39;&#39;&#39;

stuff = ET.fromstring(input)
lst = stuff.findall(&#39;users/user&#39;)
print(&#39;User count:&#39;, len(lst))

for item in lst:
    print(&#39;Name&#39;, item.find(&#39;name&#39;).text)
    print(&#39;Id&#39;, item.find(&#39;id&#39;).text)
    print(&#39;Attribute&#39;, item.get(&quot;x&quot;))

# Code: http://www.py4e.com/code3/xml2.py</code></pre>
<p>The <code>findall</code> method retrieves a Python list of subtrees that represent the <code>user</code> structures in the XML tree. Then we can write a <code>for</code> loop that looks at each of the user nodes, and prints the <code>name</code> and <code>id</code> text elements as well as the <code>x</code> attribute from the <code>user</code> node.</p>
<pre><code>User count: 2
Name Chuck
Id 001
Attribute 2
Name Brent
Id 009
Attribute 7</code></pre>
<h2 id="javascript-object-notation---json">JavaScript Object Notation - JSON</h2>
<p> </p>
<p>The JSON format was inspired by the object and array format used in the JavaScript language. But since Python was invented before JavaScript, Python's syntax for dictionaries and lists influenced the syntax of JSON. So the format of JSON is nearly identical to a combination of Python lists and dictionaries.</p>
<p>Here is a JSON encoding that is roughly equivalent to the simple XML from above:</p>
<pre class="json"><code>{
  &quot;name&quot; : &quot;Chuck&quot;,
  &quot;phone&quot; : {
    &quot;type&quot; : &quot;intl&quot;,
    &quot;number&quot; : &quot;+1 734 303 4456&quot;
   },
   &quot;email&quot; : {
     &quot;hide&quot; : &quot;yes&quot;
   }
}</code></pre>
<p>You will notice some differences. First, in XML, we can add attributes like &quot;intl&quot; to the &quot;phone&quot; tag. In JSON, we simply have key-value pairs. Also the XML &quot;person&quot; tag is gone, replaced by a set of outer curly braces.</p>
<p>In general, JSON structures are simpler than XML because JSON has fewer capabilities than XML. But JSON has the advantage that it maps <em>directly</em> to some combination of dictionaries and lists. And since nearly all programming languages have something equivalent to Python's dictionaries and lists, JSON is a very natural format to have two cooperating programs exchange data.</p>
<p>JSON is quickly becoming the format of choice for nearly all data exchange between applications because of its relative simplicity compared to XML.</p>
<h2 id="parsing-json">Parsing JSON</h2>
<p>We construct our JSON by nesting dictionaries (objects) and lists as needed. In this example, we represent a list of users where each user is a set of key-value pairs (i.e., a dictionary). So we have a list of dictionaries.</p>
<p>In the following program, we use the built-in <em>json</em> library to parse the JSON and read through the data. Compare this closely to the equivalent XML data and code above. The JSON has less detail, so we must know in advance that we are getting a list and that the list is of users and each user is a set of key-value pairs. The JSON is more succinct (an advantage) but also is less self-describing (a disadvantage).</p>
<pre class="python"><code>import json

data = &#39;&#39;&#39;
[
  { &quot;id&quot; : &quot;001&quot;,
    &quot;x&quot; : &quot;2&quot;,
    &quot;name&quot; : &quot;Chuck&quot;
  } ,
  { &quot;id&quot; : &quot;009&quot;,
    &quot;x&quot; : &quot;7&quot;,
    &quot;name&quot; : &quot;Chuck&quot;
  }
]&#39;&#39;&#39;

info = json.loads(data)
print(&#39;User count:&#39;, len(info))

for item in info:
    print(&#39;Name&#39;, item[&#39;name&#39;])
    print(&#39;Id&#39;, item[&#39;id&#39;])
    print(&#39;Attribute&#39;, item[&#39;x&#39;])

# Code: http://www.py4e.com/code3/json2.py</code></pre>
<p>If you compare the code to extract data from the parsed JSON and XML you will see that what we get from <em>json.loads()</em> is a Python list which we traverse with a <code>for</code> loop, and each item within that list is a Python dictionary. Once the JSON has been parsed, we can use the Python index operator to extract the various bits of data for each user. We don't have to use the JSON library to dig through the parsed JSON, since the returned data is simply native Python structures.</p>
<p>The output of this program is exactly the same as the XML version above.</p>
<pre><code>User count: 2
Name Chuck
Id 001
Attribute 2
Name Brent
Id 009
Attribute 7</code></pre>
<p>In general, there is an industry trend away from XML and towards JSON for web services. Because the JSON is simpler and more directly maps to native data structures we already have in programming languages, the parsing and data extraction code is usually simpler and more direct when using JSON. But XML is more self-descriptive than JSON and so there are some applications where XML retains an advantage. For example, most word processors store documents internally using XML rather than JSON.</p>
<h2 id="application-programming-interfaces">Application Programming Interfaces</h2>
<p>We now have the ability to exchange data between applications using HyperText Transport Protocol (HTTP) and a way to represent complex data that we are sending back and forth between these applications using eXtensible Markup Language (XML) or JavaScript Object Notation (JSON).</p>
<p>The next step is to begin to define and document &quot;contracts&quot; between applications using these techniques. The general name for these application-to-application contracts is <em>Application Program Interfaces</em> or APIs. When we use an API, generally one program makes a set of <em>services</em> available for use by other applications and publishes the APIs (i.e., the &quot;rules&quot;) that must be followed to access the services provided by the program.</p>
<p>When we begin to build our programs where the functionality of our program includes access to services provided by other programs, we call the approach a <em>Service-Oriented Architecture</em> or SOA. A SOA approach is one where our overall application makes use of the services of other applications. A non-SOA approach is where the application is a single standalone application which contains all of the code necessary to implement the application.</p>
<p>We see many examples of SOA when we use the web. We can go to a single web site and book air travel, hotels, and automobiles all from a single site. The data for hotels is not stored on the airline computers. Instead, the airline computers contact the services on the hotel computers and retrieve the hotel data and present it to the user. When the user agrees to make a hotel reservation using the airline site, the airline site uses another web service on the hotel systems to actually make the reservation. And when it comes time to charge your credit card for the whole transaction, still other computers become involved in the process.</p>
<div class="figure">
<img src="../images/soa.svg" alt="Service Oriented Architecture" />
<p class="caption">Service Oriented Architecture</p>
</div>
<p>A Service-Oriented Architecture has many advantages including: (1) we always maintain only one copy of data (this is particularly important for things like hotel reservations where we do not want to over-commit) and (2) the owners of the data can set the rules about the use of their data. With these advantages, an SOA system must be carefully designed to have good performance and meet the user's needs.</p>
<p>When an application makes a set of services in its API available over the web, we call these <em>web services</em>.</p>
<h2 id="google-geocoding-web-service">Google geocoding web service</h2>
<p>  </p>
<p>Google has an excellent web service that allows us to make use of their large database of geographic information. We can submit a geographical search string like &quot;Ann Arbor, MI&quot; to their geocoding API and have Google return its best guess as to where on a map we might find our search string and tell us about the landmarks nearby.</p>
<p>The geocoding service is free but rate limited so you cannot make unlimited use of the API in a commercial application. But if you have some survey data where an end user has entered a location in a free-format input box, you can use this API to clean up your data quite nicely.</p>
<p><em>When you are using a free API like Google's geocoding API, you need to be respectful in your use of these resources. If too many people abuse the service, Google might drop or significantly curtail its free service.</em></p>
<p></p>
<p>You can read the online documentation for this service, but it is quite simple and you can even test it using a browser by typing the following URL into your browser:</p>
<p><a href="http://maps.googleapis.com/maps/api/geocode/json?address=Ann+Arbor%2C+MI">http://maps.googleapis.com/maps/api/geocode/json?address=Ann+Arbor%2C+MI</a></p>
<p>Make sure to unwrap the URL and remove any spaces from the URL before pasting it into your browser.</p>
<p>The following is a simple application to prompt the user for a search string, call the Google geocoding API, and extract information from the returned JSON.</p>
<pre class="python"><code>import urllib.request, urllib.parse, urllib.error
import json

# Note that Google is increasingly requiring keys
# for this API
serviceurl = &#39;http://maps.googleapis.com/maps/api/geocode/json?&#39;

while True:
    address = input(&#39;Enter location: &#39;)
    if len(address) &lt; 1: break

    url = serviceurl + urllib.parse.urlencode(
        {&#39;address&#39;: address})

    print(&#39;Retrieving&#39;, url)
    uh = urllib.request.urlopen(url)
    data = uh.read().decode()
    print(&#39;Retrieved&#39;, len(data), &#39;characters&#39;)

    try:
        js = json.loads(data)
    except:
        js = None

    if not js or &#39;status&#39; not in js or js[&#39;status&#39;] != &#39;OK&#39;:
        print(&#39;==== Failure To Retrieve ====&#39;)
        print(data)
        continue

    print(json.dumps(js, indent=4))

    lat = js[&quot;results&quot;][0][&quot;geometry&quot;][&quot;location&quot;][&quot;lat&quot;]
    lng = js[&quot;results&quot;][0][&quot;geometry&quot;][&quot;location&quot;][&quot;lng&quot;]
    print(&#39;lat&#39;, lat, &#39;lng&#39;, lng)
    location = js[&#39;results&#39;][0][&#39;formatted_address&#39;]
    print(location)

# Code: http://www.py4e.com/code3/geojson.py</code></pre>
<p>The program takes the search string and constructs a URL with the search string as a properly encoded parameter and then uses <em>urllib</em> to retrieve the text from the Google geocoding API. Unlike a fixed web page, the data we get depends on the parameters we send and the geographical data stored in Google's servers.</p>
<p>Once we retrieve the JSON data, we parse it with the <em>json</em> library and do a few checks to make sure that we received good data, then extract the information that we are looking for.</p>
<p>The output of the program is as follows (some of the returned JSON has been removed):</p>
<pre><code>$ python3 geojson.py
Enter location: Ann Arbor, MI
Retrieving http://maps.googleapis.com/maps/api/
  geocode/json?address=Ann+Arbor%2C+MI
Retrieved 1669 characters</code></pre>
<pre class="json"><code>{
    &quot;status&quot;: &quot;OK&quot;,
    &quot;results&quot;: [
        {
            &quot;geometry&quot;: {
                &quot;location_type&quot;: &quot;APPROXIMATE&quot;,
                &quot;location&quot;: {
                    &quot;lat&quot;: 42.2808256,
                    &quot;lng&quot;: -83.7430378
                }
            },
            &quot;address_components&quot;: [
                {
                    &quot;long_name&quot;: &quot;Ann Arbor&quot;,
                    &quot;types&quot;: [
                        &quot;locality&quot;,
                        &quot;political&quot;
                    ],
                    &quot;short_name&quot;: &quot;Ann Arbor&quot;
                }
            ],
            &quot;formatted_address&quot;: &quot;Ann Arbor, MI, USA&quot;,
            &quot;types&quot;: [
                &quot;locality&quot;,
                &quot;political&quot;
            ]
        }
    ]
}
lat 42.2808256 lng -83.7430378
Ann Arbor, MI, USA</code></pre>
<pre><code>Enter location:</code></pre>
<p>You can download <a href="http://www.py4e.com/code3/geoxml.py">www.py4e.com/code3/geoxml.py</a> to explore the XML variant of the Google geocoding API.</p>
<h2 id="security-and-api-usage">Security and API usage</h2>
<p> </p>
<p>It is quite common that you need some kind of &quot;API key&quot; to make use of a vendor's API. The general idea is that they want to know who is using their services and how much each user is using. Perhaps they have free and pay tiers of their services or have a policy that limits the number of requests that a single individual can make during a particular time period.</p>
<p>Sometimes once you get your API key, you simply include the key as part of POST data or perhaps as a parameter on the URL when calling the API.</p>
<p>Other times, the vendor wants increased assurance of the source of the requests and so they add expect you to send cryptographically signed messages using shared keys and secrets. A very common technology that is used to sign requests over the Internet is called <em>OAuth</em>. You can read more about the OAuth protocol at <a href="http://www.oauth.net">www.oauth.net</a>.</p>
<p>As the Twitter API became increasingly valuable, Twitter went from an open and public API to an API that required the use of OAuth signatures on each API request. Thankfully there are still a number of convenient and free OAuth libraries so you can avoid writing an OAuth implementation from scratch by reading the specification. These libraries are of varying complexity and have varying degrees of richness. The OAuth web site has information about various OAuth libraries.</p>
<p>For this next sample program we will download the files <em>twurl.py</em>, <em>hidden.py</em>, <em>oauth.py</em>, and <em>twitter1.py</em> from <a href="http://www.py4e.com/code3">www.py4e.com/code</a> and put them all in a folder on your computer.</p>
<p>To make use of these programs you will need to have a Twitter account, and authorize your Python code as an application, set up a key, secret, token and token secret. You will edit the file <em>hidden.py</em> and put these four strings into the appropriate variables in the file:</p>
<pre class="python"><code># Keep this file separate

# https://apps.twitter.com/
# Create new App and get the four strings

def oauth():
    return {&quot;consumer_key&quot;: &quot;h7Lu...Ng&quot;,
            &quot;consumer_secret&quot;: &quot;dNKenAC3New...mmn7Q&quot;,
            &quot;token_key&quot;: &quot;10185562-eibxCp9n2...P4GEQQOSGI&quot;,
            &quot;token_secret&quot;: &quot;H0ycCFemmC4wyf1...qoIpBo&quot;}

# Code: http://www.py4e.com/code3/hidden.py</code></pre>
<p>The Twitter web service are accessed using a URL like this:</p>
<p><a href="https://api.twitter.com/1.1/statuses/user_timeline.json" class="uri">https://api.twitter.com/1.1/statuses/user_timeline.json</a></p>
<p>But once all of the security information has been added, the URL will look more like:</p>
<pre><code>https://api.twitter.com/1.1/statuses/user_timeline.json?count=2
&amp;oauth_version=1.0&amp;oauth_token=101...SGI&amp;screen_name=drchuck
&amp;oauth_nonce=09239679&amp;oauth_timestamp=1380395644
&amp;oauth_signature=rLK...BoD&amp;oauth_consumer_key=h7Lu...GNg
&amp;oauth_signature_method=HMAC-SHA1</code></pre>
<p>You can read the OAuth specification if you want to know more about the meaning of the various parameters that are added to meet the security requirements of OAuth.</p>
<p>For the programs we run with Twitter, we hide all the complexity in the files <em>oauth.py</em> and <em>twurl.py</em>. We simply set the secrets in <em>hidden.py</em> and then send the desired URL to the <em>twurl.augment()</em> function and the library code adds all the necessary parameters to the URL for us.</p>
<p>This program retrieves the timeline for a particular Twitter user and returns it to us in JSON format in a string. We simply print the first 250 characters of the string:</p>
<pre class="python"><code>import urllib.request, urllib.parse, urllib.error
import twurl
import ssl

# https://apps.twitter.com/
# Create App and get the four strings, put them in hidden.py

TWITTER_URL = &#39;https://api.twitter.com/1.1/statuses/user_timeline.json&#39;

# Ignore SSL certificate errors
ctx = ssl.create_default_context()
ctx.check_hostname = False
ctx.verify_mode = ssl.CERT_NONE

while True:
    print(&#39;&#39;)
    acct = input(&#39;Enter Twitter Account:&#39;)
    if (len(acct) &lt; 1): break
    url = twurl.augment(TWITTER_URL,
                        {&#39;screen_name&#39;: acct, &#39;count&#39;: &#39;2&#39;})
    print(&#39;Retrieving&#39;, url)
    connection = urllib.request.urlopen(url, context=ctx)
    data = connection.read().decode()
    print(data[:250])
    headers = dict(connection.getheaders())
    # print headers
    print(&#39;Remaining&#39;, headers[&#39;x-rate-limit-remaining&#39;])

# Code: http://www.py4e.com/code3/twitter1.py</code></pre>

<p>When the program runs it produces the following output:</p>
<pre><code>Enter Twitter Account:drchuck
Retrieving https://api.twitter.com/1.1/ ...
[{&quot;created_at&quot;:&quot;Sat Sep 28 17:30:25 +0000 2013&quot;,&quot;
id&quot;:384007200990982144,&quot;id_str&quot;:&quot;384007200990982144&quot;,
&quot;text&quot;:&quot;RT @fixpert: See how the Dutch handle traffic
intersections: http:\/\/t.co\/tIiVWtEhj4\n#brilliant&quot;,
&quot;source&quot;:&quot;web&quot;,&quot;truncated&quot;:false,&quot;in_rep
Remaining 178

Enter Twitter Account:fixpert
Retrieving https://api.twitter.com/1.1/ ...
[{&quot;created_at&quot;:&quot;Sat Sep 28 18:03:56 +0000 2013&quot;,
&quot;id&quot;:384015634108919808,&quot;id_str&quot;:&quot;384015634108919808&quot;,
&quot;text&quot;:&quot;3 months after my freak bocce ball accident,
my wedding ring fits again! :)\n\nhttps:\/\/t.co\/2XmHPx7kgX&quot;,
&quot;source&quot;:&quot;web&quot;,&quot;truncated&quot;:false,
Remaining 177

Enter Twitter Account:</code></pre>
<p>Along with the returned timeline data, Twitter also returns metadata about the request in the HTTP response headers. One header in particular, <em>x-rate-limit-remaining</em>, informs us how many more requests we can make before we will be shut off for a short time period. You can see that our remaining retrievals drop by one each time we make a request to the API.</p>
<p>In the following example, we retrieve a user's Twitter friends, parse the returned JSON, and extract some of the information about the friends. We also dump the JSON after parsing and &quot;pretty-print&quot; it with an indent of four characters to allow us to pore through the data when we want to extract more fields.</p>
<pre class="python"><code>import urllib.request, urllib.parse, urllib.error
import twurl
import json
import ssl

# https://apps.twitter.com/
# Create App and get the four strings, put them in hidden.py

TWITTER_URL = &#39;https://api.twitter.com/1.1/friends/list.json&#39;

# Ignore SSL certificate errors
ctx = ssl.create_default_context()
ctx.check_hostname = False
ctx.verify_mode = ssl.CERT_NONE

while True:
    print(&#39;&#39;)
    acct = input(&#39;Enter Twitter Account:&#39;)
    if (len(acct) &lt; 1): break
    url = twurl.augment(TWITTER_URL,
                        {&#39;screen_name&#39;: acct, &#39;count&#39;: &#39;5&#39;})
    print(&#39;Retrieving&#39;, url)
    connection = urllib.request.urlopen(url, context=ctx)
    data = connection.read().decode()

    js = json.loads(data)
    print(json.dumps(js, indent=2))

    headers = dict(connection.getheaders())
    print(&#39;Remaining&#39;, headers[&#39;x-rate-limit-remaining&#39;])

    for u in js[&#39;users&#39;]:
        print(u[&#39;screen_name&#39;])
        if &#39;status&#39; not in u:
            print(&#39;   * No status found&#39;)
            continue
        s = u[&#39;status&#39;][&#39;text&#39;]
        print(&#39;  &#39;, s[:50])

# Code: http://www.py4e.com/code3/twitter2.py</code></pre>

<p>Since the JSON becomes a set of nested Python lists and dictionaries, we can use a combination of the index operation and <code>for</code> loops to wander through the returned data structures with very little Python code.</p>
<p>The output of the program looks as follows (some of the data items are shortened to fit on the page):</p>
<pre><code>Enter Twitter Account:drchuck
Retrieving https://api.twitter.com/1.1/friends ...
Remaining 14</code></pre>
<pre class="json"><code>{
    &quot;next_cursor&quot;: 1444171224491980205,
    &quot;users&quot;: [
        {
            &quot;id&quot;: 662433,
            &quot;followers_count&quot;: 28725,
            &quot;status&quot;: {
                &quot;text&quot;: &quot;@jazzychad I just bought one .__.&quot;,
                &quot;created_at&quot;: &quot;Fri Sep 20 08:36:34 +0000 2013&quot;,
                &quot;retweeted&quot;: false,
            },
            &quot;location&quot;: &quot;San Francisco, California&quot;,
            &quot;screen_name&quot;: &quot;leahculver&quot;,
            &quot;name&quot;: &quot;Leah Culver&quot;,
        },
        {
            &quot;id&quot;: 40426722,
            &quot;followers_count&quot;: 2635,
            &quot;status&quot;: {
                &quot;text&quot;: &quot;RT @WSJ: Big employers like Google ...&quot;,
                &quot;created_at&quot;: &quot;Sat Sep 28 19:36:37 +0000 2013&quot;,
            },
            &quot;location&quot;: &quot;Victoria Canada&quot;,
            &quot;screen_name&quot;: &quot;_valeriei&quot;,
            &quot;name&quot;: &quot;Valerie Irvine&quot;,
    ],
    &quot;next_cursor_str&quot;: &quot;1444171224491980205&quot;
}</code></pre>
<pre><code>leahculver
   @jazzychad I just bought one .__.
_valeriei
   RT @WSJ: Big employers like Google, AT&amp;amp;T are h
ericbollens
   RT @lukew: sneak peek: my LONG take on the good &amp;a
halherzog
   Learning Objects is 10. We had a cake with the LO,
scweeker
   @DeviceLabDC love it! Now where so I get that &quot;etc

Enter Twitter Account:</code></pre>
<p>The last bit of the output is where we see the for loop reading the five most recent &quot;friends&quot; of the <em>drchuck</em> Twitter account and printing the most recent status for each friend. There is a great deal more data available in the returned JSON. If you look in the output of the program, you can also see that the &quot;find the friends&quot; of a particular account has a different rate limitation than the number of timeline queries we are allowed to run per time period.</p>
<p>These secure API keys allow Twitter to have solid confidence that they know who is using their API and data and at what level. The rate-limiting approach allows us to do simple, personal data retrievals but does not allow us to build a product that pulls data from their API millions of times per day.</p>
<h2 id="glossary">Glossary</h2>
<dl>
<dt>API</dt>
<dd>Application Program Interface - A contract between applications that defines the patterns of interaction between two application components.
</dd>
<dt>ElementTree</dt>
<dd>A built-in Python library used to parse XML data.
</dd>
<dt>JSON</dt>
<dd>JavaScript Object Notation. A format that allows for the markup of structured data based on the syntax of JavaScript Objects.
</dd>
<dt>SOA</dt>
<dd>Service-Oriented Architecture. When an application is made of components connected across a network.
</dd>
<dt>XML</dt>
<dd>eXtensible Markup Language. A format that allows for the markup of structured data.
</dd>
</dl>
<h2 id="exercises">Exercises</h2>
<p><strong>Exercise 1:</strong> Change either the <a href="http://www.py4e.com/code3/geojson.py">www.py4e.com/code3/geojson.py</a> or <a href="http://www.py4e.com/code3/geoxml.py">www.py4e.com/code3/geoxml.py</a> to print out the two-character country code from the retrieved data. Add error checking so your program does not traceback if the country code is not there. Once you have it working, search for &quot;Atlantic Ocean&quot; and make sure it can handle locations that are not in any country.</p>
</body>
</html>
<?php if ( file_exists("../bookfoot.php") ) {
  $HTML_FILE = basename(__FILE__);
  $HTML = ob_get_contents();
  ob_end_clean();
  require_once "../bookfoot.php";
}?>
