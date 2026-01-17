@php
    $host = website()->getHost();
    $siteName = generate_site_name_from_host($host);
    $email = 'admin@' . $host;
@endphp
<p><strong>Effective Date:</strong>{{ date('d-m-Y') }}</p>
<p>Welcome to {{ $siteName }} ("Website," "we," "our," "us"). By accessing or using our website, you agree to comply with and be bound by the following Terms and Conditions. If you do not agree with any part of these terms, please do not use our website.</p>
<h3>1.&nbsp;<strong>Use of the Website</strong></h3>
<ul data-spread="false">
    <li>&nbsp;You must be at least 18 years old to use this website.</li>
    <li>&nbsp;You agree to use the website only for lawful purposes and in a way that does not infringe on the rights of others.</li>
    <li>&nbsp;We reserve the right to modify or discontinue any part of the website at any time.</li>
</ul>
<h3>2.&nbsp;<strong>Intellectual Property Rights</strong></h3>
<ul data-spread="false">
    <li>&nbsp;All content, including text, images, graphics, logos, and software, is owned by us or its licensors and is protected by intellectual property laws.</li>
    <li>&nbsp;You may not copy, reproduce, distribute, or use any content without prior written permission from us.</li>
</ul>
<h3>3.&nbsp;<strong>User Content</strong></h3>
<ul data-spread="false">
    <li>&nbsp;If you submit or post content on {{ $siteName }}, you grant us a non-exclusive, royalty-free license to use, reproduce, modify, and distribute your content.</li>
    <li>&nbsp;You are responsible for ensuring that your content does not violate any laws or rights of third parties.</li>
</ul>
<h3>4.&nbsp;<strong>Third-Party Links</strong></h3>
<p>Our website may contain links to third-party websites. We are not responsible for the content, privacy policies, or practices of any third-party websites.</p>
<h3>5.&nbsp;<strong>Disclaimers and Limitation of Liability</strong></h3>
<ul data-spread="false">
    <li>&nbsp;{{ $siteName }} provides information "as is" without warranties of any kind.</li>
    <li>&nbsp;We do not guarantee the accuracy, reliability, or completeness of the content.</li>
    <li>&nbsp;We are not liable for any direct, indirect, or consequential damages resulting from the use of our website.</li>
</ul>
<h3>6.&nbsp;<strong>Privacy Policy</strong></h3>
<p>Your use of {{ $siteName }} is also governed by our&nbsp;<strong>Privacy Policy</strong>. Please review it to understand how we collect and use your data.</p>
<h3>7.&nbsp;<strong>Changes to Terms and Conditions</strong></h3>
<p>We may update these Terms and Conditions at any time. Continued use of the website after changes constitute your acceptance of the new terms.</p>
<h3>8.&nbsp;<strong>Contact Information</strong></h3>
<p>If you have any questions about these Terms and Conditions, please contact us at:</p>
<p><strong>Email:</strong>&nbsp;<a href="mailto:{{ $email }}">{{ $email }}</a></p>
<p>By using {{ $siteName }}, you acknowledge that you have read, understood, and agreed to these Terms and Conditions.</p>
