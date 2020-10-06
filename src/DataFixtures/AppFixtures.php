<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use App\Entity\BlogCategory;
use App\Entity\BlogPost;
use App\Entity\Friend;
use App\Entity\Project;
use App\Entity\SocialLink;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $toBeSaved = [];
        
        $admin = new User();
        $admin->setUsername("kevin");
        $admin->setPassword("$2y$13$1bFjHvEURa6peKT48XAoK.1eB0UFrSiAklBBhx372s/RqKy9L95SG"); // hunter1
        $admin->setPublicName("Kevin Kandlbinder");
        $admin->setRoles(["ROLE_SUPER_ADMIN"]);
        $admin->setEmail("dummy@1in9.net");
        $toBeSaved[] = $admin;

        $generalCat = new BlogCategory();
        $generalCat->setLanguageKey("general");
        $generalCat->setUrlName("general");
        $generalCat->setDescriptionLanguageKey("generalDescription");
        $toBeSaved[] = $generalCat;

        $post01 = new BlogPost();
        $post01->setCategory($generalCat);
        $post01->setAuthor($admin);
        $post01->setTitle(["de"=>"Hallo Welt!", "en"=>"Hello World!"]);
        $post01->setContent(["de"=> "```php\necho \"Hallo Welt!\";\n```\n\nHallo Welt, ich bin Kevin. Wie sich an der Domain erraten lässt bin ich der Besitzer der Seite. Hier stelle ich alles rein was ich veröffentlichen möchte wie zum Beispiel Blog-Posts, Projekte und Fotos. Gerade ist die Seite noch in der Entwicklung und noch nicht alle Module sind aktiv.", "en"=> "```php\necho \"Hello World!\";\n```\n\nHello World, this is Kevin. As you might have guessed from the domain of this site I am the owner. This site is my personal platform for everything I want to put out there like blog posts, projects and photos. Right now the site is under development, so there are some modules which are not enabled yet."]);
        $post01->setSummary(["de"=> "Hallo Welt, ich bin Kevin und das hier ist meine Seite!", "en"=> "Hello Wold, this is Kevin and this is my site."]);
        $post01->setImage("/assets/images/mario-caruso-770233-unsplash.jpg");
        $post01->setPublishTime(new \DateTime());
        $post01->setVisible(true);
        $toBeSaved[] = $post01;

        $friend01 = new Friend();
        $friend01->setFriendlyName("Max Mustermann");
        $friend01->setUrl("https://example.com");
        $friend01->setTwitter("@Unkn0wnKevin");
        $friend01->setBio(["en"=>"Text here.", "de"=>"Text hier."]);
        $friend01->setImage("https://source.unsplash.com/2Ua6nlknCHs/500x500");
        $friend01->setTitle(["en"=>"Testfriend", "de"=>"Testfreund"]);
        $toBeSaved[] = $friend01;

        $project01 = new Project();
        $project01->setName(["*"=>"Testing"]);
        $project01->setUrlName("testing");
        $project01->setHeadline(["de"=>"Der beste Test ever!", "en"=>"The best test ever!"]);
        $project01->setExternalURL("https://example.com");
        $project01->setRedirectToExternal(false);
        $project01->setAbout(["de"=>"Der beste Test ever!", "en"=>"The best test ever!"]);
        $project01->setImage("/assets/images/projects/KevinK.dev.png");
        $project01->setModules([
            "links" => [
              [
                "type"=> "github",
                "url"=> "https://github.com/Unkn0wnCat/KevinK.dev"
              ]
            ]
          ]);
        $project01->setIndicatorTranslationString("statusLive");
        $project01->setIndicatorClass("activityIndicatorBlue");
        $project01->setVisible(true);
        $toBeSaved[] = $project01;

        $link = new SocialLink();
        $link->setPlatformName("Twitter");
        $link->setImage("https://source.unsplash.com/8cMPxOqkLE8/500x500");
        $link->setHandle("@Unkn0wnKevin");
        $link->setUrl("https://twitter.com/unkn0wnkevin");
        $link->setOrderPriority(100);
        $toBeSaved[] = $link;


        foreach($toBeSaved as $saveMe) {
            $manager->persist($saveMe);
        }
        $manager->flush();
    }
}
