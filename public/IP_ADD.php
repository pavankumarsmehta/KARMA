<?php 
$hostname = 'hbastore.com';

$ipv4 = gethostbyname($hostname);
$ipv6 = gethostbynamel($hostname);

echo "IPv4 Address: $ipv4<br>";
echo "IPv6 Addresses: ";
foreach ($ipv6 as $address) {
    echo "$address<br>";
}
?>