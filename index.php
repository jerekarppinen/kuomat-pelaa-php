<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <meta name="robots" content="index, follow">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Kuomat Pelaa on rento suomalainen videopelipodcast. Harri ja Jere keskustelevat peleistä, joita he tykkäävät pelata. Kuuntele uusimmat jaksot täältä.">
    <meta name="keywords" content="Kuomat Pelaa, videopelipodcast, suomalainen podcast, pelipodcast, PlayStation, PC, Switch, Steam Deck">
    <meta name="author" content="Kuomat Pelaa Podcast">
    <meta property="og:title" content="Kuomat Pelaa - Suomalainen videopelipodcast">
    <meta property="og:description" content="Harri ja Jere keskustelevat peleistä, joita he tykkäävät pelata. Kuuntele uusimmat jaksot Kuomat Pelaa -podcastissa.">
    <meta property="og:image" content="https://www.kuomatpelaa.com/logo.jpg">
    <meta property="og:url" content="https://www.kuomatpelaa.com/">
    <meta property="og:site_name" content="Kuomat Pelaa Podcast">
    <meta property="og:locale" content="fi_FI">
    <meta property="og:type" content="website">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Kuomat Pelaa - Podcast">
    <meta name="twitter:description" content="Suomalainen pelipodcast – Harri ja Jere pelaavat ja keskustelevat.">
    <meta name="twitter:image" content="https://www.kuomatpelaa.com/logo.jpg">
    <link rel="canonical" href="https://www.kuomatpelaa.com/">
    <link rel="alternate" type="application/rss+xml" title="Kuomat Pelaa Podcast RSS" href="https://anchor.fm/s/e698ed4c/podcast/rss">
    <title>Kuomat Pelaa - Podcast</title>
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="stylesheet" href="styles.css?v=<?php echo filemtime('styles.css'); ?>">
    <script defer src="https://cloud.umami.is/script.js" data-website-id="9d33c68b-825b-4f14-835b-c53e631c5cd6"></script>
    <script type="application/ld+json">
    {
    "@context": "https://schema.org",
    "@type": "PodcastSeries",
    "name": "Kuomat Pelaa",
    "description": "Kuomat Pelaa on rento suomalainen videopelipodcast. Harri ja Jere keskustelevat peleistä, joita he tykkäävät pelata.",
    "url": "https://www.kuomatpelaa.com/",
    "image": "https://www.kuomatpelaa.com/logo.jpg",
    "author": {
        "@type": "Person",
        "name": ["Harri Lappalainen", "Jere Karppinen"]
    },
    "sameAs": [
        "https://www.instagram.com/kuomatpelaa/",
        "https://open.spotify.com/show/yourSpotifyShowID",
        "https://podcasts.apple.com/fi/podcast/kuomat-pelaa/idYOURAPPLEID"
        ]
    }
    </script>
</head>
<body>
    <?php
    // RSS reader functions
    $rss_url = "https://anchor.fm/s/e698ed4c/podcast/rss";

    function get_episodes($rss_url) {
        $xml = simplexml_load_file($rss_url);
        
        if ($xml === false) {
            return [];
        }
        
        $items = $xml->channel->item;
        $total_episodes = count($items);
        $episodes = [];
        
        $i = 0;
        foreach ($items as $item) {
            $episode_number = $total_episodes - $i;
            $episode = Episode::from_rss_item($item, $episode_number);
            $episodes[] = $episode;
            $i++;
        }
        
        return $episodes;
    }

    class Episode {
        public $episode_number;
        public $title;
        public $description;
        public $pub_date;
        public $link;
        public $guid;
        
        public static function from_rss_item($item, $episode_number) {
            $episode = new self();
            $episode->episode_number = $episode_number;
            $episode->title = (string)$item->title;
            $episode->description = (string)$item->description;
            $episode->pub_date = (string)$item->pubDate;
            $episode->link = (string)$item->link;
            $episode->guid = (string)$item->guid;
            return $episode;
        }
    }

    function formatDate($dateString) {
        return date('j.n.Y', strtotime($dateString));
    }

    function getTruncatedDescription($description, $limit = 150) {
        // Strip HTML tags to get plain text
        $textContent = strip_tags($description);
        
        return strlen($textContent) > $limit 
            ? substr($textContent, 0, $limit) . '...'
            : $textContent;
    }

    // Fetch episodes
    $episodes = get_episodes($rss_url);
    $loading = false;
    ?>

    <script>
        function toggleDescription(guid) {
            const description = document.getElementById('desc-' + guid);
            const button = document.getElementById('btn-' + guid);
            const isExpanded = description.getAttribute('data-expanded') === 'true';
            
            if (isExpanded) {
                // Show truncated version
                description.textContent = description.getAttribute('data-truncated');
                description.setAttribute('data-expanded', 'false');
                button.textContent = 'Näytä loput';
            } else {
                // Show full version (as HTML)
                description.innerHTML = description.getAttribute('data-full');
                description.setAttribute('data-expanded', 'true');
                button.textContent = 'Näytä vähemmän';
            }
        }
    </script>

    <div>
        <header>
            <div class="container">
                <div class="header-content">
                    <div class="logo">
                        <img src="logo.jpg" alt="Logo" />
                    </div>
                    <p class="slogan">Rento pelipodcast, jossa Kuomat juttelevat peleistä, joita he tykkäävät pelata</p>
                </div>
            </div>
        </header>

        <section class="hosts-section">
            <div class="container">
                <h2 class="section-title">Kuomat</h2>
                <div class="hosts-container">
                    <div class="host-card">
                        <img src="harri.jpeg" alt="Host 1" class="host-image" />
                        <h3 class="host-name">Harri Lappalainen</h3>
                        <p class="host-bio">Harri on lupsakka savolainen, joka nautiskelee erityisesti vuoropohjaisista roolipeleistä, taistelupeleistä ja roguelikeistä. Kaikenlainen retro toimii myös, ja Harrilta löytyykin useampi vanhempi pelikonsoli joiden antia hän saattaa välillä esitellä. Hän pelaa Nintendo Switchillä, PS5:lla ja nuhapumppu PC:llä.</p>
                    </div>
                    <div class="host-card">
                        <img src="jere.jpeg" alt="Host 2" class="host-image" />
                        <h3 class="host-name">Jere Karppinen</h3>
                        <p class="host-bio">Jere on vitsiä murjova pohjoiskarjalainen, joka aloitti seikkailupelien koluamisen jo hyvin nuorella iällä ja on edelleen sillä tiellä. Hän on valmis kuitenkin kokeilemaan monenlaisia pelejä ja saattaa joskus yllättää sillä, mitä jaksoon tuo! Jere käyttää pelaamiseen PC:tä, PS5 ja Steam Deckiä.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="socials-section">
            <div class="container">
                <h2 class="section-title">Instagram</h2>
                <div class="socials-container">
                    <a href="https://www.instagram.com/kuomatpelaa/" 
                       target="_blank" 
                       rel="noopener noreferrer" 
                       class="social-link">
                        <svg class="social-icon" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                        </svg>
                        <span>@kuomatpelaa</span>
                    </a>
                </div>
            </div>
        </section>

        <div class="search-container">
            <div class="container">
                <form class="search-form" action="#" method="get">
                    <input 
                        type="text" 
                        class="search-input" 
                        placeholder="Hae jaksoja hakusanalla" 
                        aria-label="Search podcasts"
                        name="q"
                    />
                </form>
            </div>
        </div>

        <section class="episodes-section">
            <div class="container">
                <h2 class="section-title">Jaksot</h2>
                <?php if ($loading): ?>
                    <div class='loading-container'>
                        <div class="spinner"></div>
                    </div>
                <?php else: ?>
                    <div class="episodes-container">
                        <?php foreach ($episodes as $episode): ?>
                            <?php 
                                $truncatedDesc = getTruncatedDescription($episode->description);
                                $needsToggle = strlen(strip_tags($episode->description)) > 150;
                            ?>
                            <div class="episode-card">
                                <div class="episode-info">
                                    <span class="episode-number"><?php echo htmlspecialchars($episode->episode_number); ?>.</span>
                                    <span class="episode-date"><?php echo formatDate($episode->pub_date); ?></span>
                                </div>
                                <h3 class="episode-title"><?php echo htmlspecialchars($episode->title); ?></h3>
                                <div 
                                    class="episode-description" 
                                    id="desc-<?php echo htmlspecialchars($episode->guid); ?>"
                                    data-expanded="false"
                                    data-truncated="<?php echo htmlspecialchars($truncatedDesc); ?>"
                                    data-full="<?php echo htmlspecialchars($episode->description); ?>"
                                >
                                    <?php echo htmlspecialchars($truncatedDesc); ?>
                                </div>
                                <div class="episode-actions">
                                    <a 
                                        href="<?php echo htmlspecialchars($episode->link); ?>" 
                                        class="episode-button" 
                                        target="_blank" 
                                        rel="noopener noreferrer"
                                    >
                                        Kuuntele Spotifyssä
                                    </a>
                                    <?php if ($needsToggle): ?>
                                        <button 
                                            class="episode-button" 
                                            id="btn-<?php echo htmlspecialchars($episode->guid); ?>"
                                            onclick="toggleDescription('<?php echo htmlspecialchars($episode->guid); ?>')"
                                        >
                                            Näytä loput
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <footer>
            <div class="container">
                <div class="social-links">
                    kuomatpelaa at gmail dot com
                </div>
                <p>&copy; 2025 Kuomat Pelaa Podcast. All Rights Reserved.</p>
                <p><a href="/tietosuojaseloste.html">Tietosuojaseloste</a></p>
            </div>
        </footer>
    </div>
    <script src="script.js?v=<?php echo filemtime('script.js'); ?>"></script> 
</body>
</html>
