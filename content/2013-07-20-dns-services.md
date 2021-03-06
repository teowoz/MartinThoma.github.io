---
layout: post
title: DNS-Services
author: Martin Thoma
date: 2013-07-20 14:46:34.000000000 +02:00
category: The Web
tags: DNS
featured_image: 2013/07/google-thumb.png
---
I've just read (ok, now it's over 3 months ago) that <a href="http://en.wikipedia.org/wiki/Google_Public_DNS">Google Public DNS</a> now supports DNSSEC (<a href="http://googleonlinesecurity.blogspot.de/2013/03/google-public-dns-now-supports-dnssec.html">source</a>).

I was curious what I currently use on my Linux Mint 14 machine. The relevant file is <strong>/etc/resolv.conf</strong>:
```text
nameserver 127.0.1.1

# OpenDNS Fallback (configured by Linux Mint in /etc/resolvconf/resolv.conf.d/tail).
nameserver 208.67.222.222
nameserver 208.67.220.220

```


<h2>namebench</h2>
A programm called <a href="https://code.google.com/p/namebench">namebench</a>  checks how fast several DNS configurations would be for you.

<figure class="aligncenter">
            <a href="../images/2013/03/namebench-300x222.png"><img src="../images/2013/03/namebench-300x222.png" alt="namebench" style="max-width:300px;max-height:222px" class="size-medium wp-image-62241"/></a>
            <figcaption class="text-center">namebench</figcaption>
        </figure>

<h2>Further reading</h2>
<ul>
  <li><a href="http://wiki.ubuntuusers.de/Dnsmasq">Dnsmasq</a> (German)</li>
  <li><a href="http://www.debian.org/doc/manuals/debian-reference/ch05.en.html#_the_hostname_resolution">Debian manual - The hostname resolution</a>: explains where 127.0.1.1 comes from</li>
</ul>
