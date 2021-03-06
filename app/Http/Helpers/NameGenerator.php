<?php

namespace App\Http\Helpers;

class NameGenerator
{

    /**
     * get a random display name
     * @return string
     */
    public static function randomName()
    {
        return self::randomColor() . ' ' . self::randomAnimal();
    }

    /**
     * @return string
     */
    public static function randomAnimal()
    {
        return ucfirst(self::ANIMALS[rand(0, count(self::ANIMALS) - 1)]);
    }

    /**
     * @return mixed
     */
    public static function randomColor()
    {
        return self::COLORS[rand(0, count(self::COLORS) - 1)];
    }

    const ANIMALS = [
        "alligator",
        "ant",
        "bear",
        "bee",
        "bird",
        "camel",
        "cat",
        "cheetah",
        "chicken",
        "chimpanzee",
        "cow",
        "crocodile",
        "deer",
        "dog",
        "dolphin",
        "duck",
        "eagle",
        "elephant",
        "fish",
        "fly",
        "fox",
        "frog",
        "giraffe",
        "goat",
        "goldfish",
        "hamster",
        "hippopotamus",
        "horse",
        "kangaroo",
        "kitten",
        "lion",
        "lobster",
        "monkey",
        "octopus",
        "owl",
        "panda",
        "pig",
        "puppy",
        "rabbit",
        "rat",
        "scorpion",
        "seal",
        "shark",
        "sheep",
        "snail",
        "snake",
        "spider",
        "squirrel",
        "tiger",
        "turtle",
        "wolf",
        "zebra",
    ];

    const COLORS = [
        "Almond",
        "Amaranth",
        "Amber",
        "Amethyst",
        "Android Green",
        "Ao",
        "Apple Green",
        "Apricot",
        "Aqua",
        "Aquamarine",
        "Army Green",
        "Arsenic",
        "Arylide Yellow",
        "Ash Grey",
        "Asparagus",
        "Auburn",
        "Aureolin",
        "Aurometalsaurus",
        "Avocado",
        "Azure",
        "Azure Mist",
        "Baby Blue",
        "Baby Pink",
        "Ball Blue",
        "Banana Mania",
        "Banana Yellow",
        "Barn Red",
        "Bazaar",
        "Beau Blue",
        "Beaver",
        "Beige",
        "Bisque",
        "Bistre",
        "Bittersweet",
        "Black",
        "Black Bean",
        "Black Leather Jacket",
        "Black Olive",
        "Blast-Off Bronze",
        "Bleu De France",
        "Blizzard Blue",
        "Blond",
        "Blue",
        "Blue Bell",
        "Blue Gray",
        "Blue-Green",
        "Blush",
        "Bole",
        "Bondi Blue",
        "Bone",
        "Bottle Green",
        "Boysenberry",
        "Brandeis Blue",
        "Brass",
        "Brick Red",
        "Bright Cerulean",
        "Bright Green",
        "Bright Lavender",
        "Bright Maroon",
        "Bright Pink",
        "Bright Turquoise",
        "Bright Ube",
        "Brilliant Lavender",
        "Brilliant Rose",
        "Brink Pink",
        "Bronze",
        "Brown",
        "Bubble Gum",
        "Bubbles",
        "Buff",
        "Bulgarian Rose",
        "Burgundy",
        "Burlywood",
        "Burnt Orange",
        "Burnt Sienna",
        "Burnt Umber",
        "Byzantine",
        "Byzantium",
        "Cadet",
        "Cadet Blue",
        "Cadet Grey",
        "Cadmium Green",
        "Cadmium Orange",
        "Cadmium Red",
        "Cadmium Yellow",
        "Cal Poly Green",
        "Cambridge Blue",
        "Camel",
        "Cameo Pink",
        "Camouflage Green",
        "Canary Yellow",
        "Candy Apple Red",
        "Candy Pink",
        "Capri",
        "Caput Mortuum",
        "Cardinal",
        "Caribbean Green",
        "Carmine",
        "Carnelian",
        "Carolina Blue",
        "Carrot Orange",
        "Catalina Blue",
        "Ceil",
        "Celadon",
        "Celadon Blue",
        "Celadon Green",
        "Celestial Blue",
        "Cerise",
        "Cerise Pink",
        "Cerulean",
        "Cerulean Blue",
        "Cerulean Frost",
        "Chamoisee",
        "Champagne",
        "Charcoal",
        "Charm Pink",
        "Chartreuse",
        "Cherry",
        "Cherry Blossom Pink",
        "Chestnut",
        "China Pink",
        "China Rose",
        "Chinese Red",
        "Chocolate",
        "Chrome Yellow",
        "Cinereous",
        "Cinnabar",
        "Cinnamon",
        "Citrine",
        "Classic Rose",
        "Cobalt",
        "Cocoa Brown",
        "Coffee",
        "Columbia Blue",
        "Congo Pink",
        "Cool Black",
        "Cool Grey",
        "Copper",
        "Copper Red",
        "Coral",
        "Coral Pink",
        "Coral Red",
        "Cordovan",
        "Corn",
        "Cornell Red",
        "Cornflower Blue",
        "Cornsilk",
        "Cosmic Latte",
        "Cotton Candy",
        "Cream",
        "Crimson",
        "Crimson Glory",
        "Cyan",
        "Daffodil",
        "Dandelion",
        "Dark Blue",
        "Dark Brown",
        "Dark Byzantium",
        "Dark Cerulean",
        "Dark Chestnut",
        "Dark Coral",
        "Dark Cyan",
        "Dark Electric Blue",
        "Dark Goldenrod",
        "Dark Gray",
        "Dark Green",
        "Dark Imperial Blue",
        "Dark Jungle Green",
        "Dark Khaki",
        "Dark Lava",
        "Dark Lavender",
        "Dark Magenta",
        "Dark Midnight Blue",
        "Dark Olive Green",
        "Dark Orange",
        "Dark Orchid",
        "Dark Pastel Blue",
        "Dark Pastel Green",
        "Dark Pastel Purple",
        "Dark Pastel Red",
        "Dark Pink",
        "Dark Powder Blue",
        "Dark Raspberry",
        "Dark Red",
        "Dark Salmon",
        "Dark Scarlet",
        "Dark Sea Green",
        "Dark Sienna",
        "Dark Slate Blue",
        "Dark Slate Gray",
        "Dark Spring Green",
        "Dark Tan",
        "Dark Tangerine",
        "Dark Taupe",
        "Dark Terra Cotta",
        "Dark Turquoise",
        "Dark Violet",
        "Dark Yellow",
        "Dartmouth Green",
        "Davy'S Grey",
        "Debian Red",
        "Denim",
        "Desert",
        "Desert Sand",
        "Dim Gray",
        "Dodger Blue",
        "Dogwood Rose",
        "Dollar Bill",
        "Drab",
        "Duke Blue",
        "Earth Yellow",
        "Ebony",
        "Ecru",
        "Eggplant",
        "Eggshell",
        "Egyptian Blue",
        "Electric Blue",
        "Electric Crimson",
        "Electric Cyan",
        "Electric Green",
        "Electric Indigo",
        "Electric Lavender",
        "Electric Lime",
        "Electric Purple",
        "Electric Ultramarine",
        "Electric Violet",
        "Electric Yellow",
        "Emerald",
        "English Lavender",
        "Eton Blue",
        "Fallow",
        "Falu Red",
        "Fandango",
        "Fashion Fuchsia",
        "Fawn",
        "Feldgrau",
        "Fern Green",
        "Ferrari Red",
        "Field Drab",
        "Fire Engine Red",
        "Firebrick",
        "Flame",
        "Flamingo Pink",
        "Flavescent",
        "Flax",
        "Floral White",
        "Fluorescent Orange",
        "Fluorescent Pink",
        "Fluorescent Yellow",
        "Folly",
        "Forest Green",
        "French Beige",
        "French Blue",
        "French Lilac",
        "French Lime",
        "French Raspberry",
        "French Rose",
        "Fuchsia",
        "Fuchsia",
        "Fuchsia Pink",
        "Fuchsia Rose",
        "Fulvous",
        "Fuzzy Wuzzy",
        "Gainsboro",
        "Gamboge",
        "Ghost White",
        "Ginger",
        "Glaucous",
        "Glitter",
        "Gold",
        "Gold",
        "Golden Brown",
        "Golden Poppy",
        "Golden Yellow",
        "Goldenrod",
        "Granny Smith Apple",
        "Gray",
        "Gray-Asparagus",
        "Gray",
        "Gray",
        "Green",
        "Green",
        "Green",
        "Green",
        "Green",
        "Green",
        "Green",
        "Green-Yellow",
        "Grullo",
        "Guppie Green",
        "Han Blue",
        "Han Purple",
        "Hansa Yellow",
        "Harlequin",
        "Harvard Crimson",
        "Harvest Gold",
        "Heart Gold",
        "Heliotrope",
        "Hollywood Cerise",
        "Honeydew",
        "Honolulu Blue",
        "Hooker'S Green",
        "Hot Magenta",
        "Hot Pink",
        "Hunter Green",
        "Iceberg",
        "Icterine",
        "Imperial Blue",
        "Inchworm",
        "India Green",
        "Indian Red",
        "Indian Yellow",
        "Indigo",
        "Indigo",
        "Indigo",
        "International Klein Blue",
        "International Orange",
        "International Orange",
        "International Orange",
        "Iris",
        "Isabelline",
        "Islamic Green",
        "Ivory",
        "Jade",
        "Jasmine",
        "Jasper",
        "Jazzberry Jam",
        "Jet",
        "Jonquil",
        "June Bud",
        "Jungle Green",
        "Kelly Green",
        "Kenyan Copper",
        "Khaki",
        "Khaki",
        "Ku Crimson",
        "La Salle Green",
        "Languid Lavender",
        "Lapis Lazuli",
        "Laser Lemon",
        "Laurel Green",
        "Lava",
        "Lavender Blue",
        "Lavender Blush",
        "Lavender",
        "Lavender Gray",
        "Lavender Indigo",
        "Lavender Magenta",
        "Lavender Mist",
        "Lavender Pink",
        "Lavender Purple",
        "Lavender Rose",
        "Lavender",
        "Lawn Green",
        "Lemon",
        "Lemon Chiffon",
        "Lemon Lime",
        "Licorice",
        "Light Apricot",
        "Light Blue",
        "Light Brown",
        "Light Carmine Pink",
        "Light Coral",
        "Light Cornflower Blue",
        "Light Crimson",
        "Light Cyan",
        "Light Fuchsia Pink",
        "Light Goldenrod Yellow",
        "Light Gray",
        "Light Green",
        "Light Khaki",
        "Light Pastel Purple",
        "Light Pink",
        "Light Red Ochre",
        "Light Salmon",
        "Light Salmon Pink",
        "Light Sea Green",
        "Light Sky Blue",
        "Light Slate Gray",
        "Light Taupe",
        "Light Thulian Pink",
        "Light Yellow",
        "Lilac",
        "Lime",
        "Lime Green",
        "Lime",
        "Limerick",
        "Lincoln Green",
        "Linen",
        "Lion",
        "Little Boy Blue",
        "Liver",
        "Lust",
        "Magenta",
        "Magenta",
        "Magenta",
        "Magic Mint",
        "Magnolia",
        "Mahogany",
        "Maize",
        "Majorelle Blue",
        "Malachite",
        "Manatee",
        "Mango Tango",
        "Mantis",
        "Mardi Gras",
        "Maroon",
        "Mauve",
        "Mauve Taupe",
        "Mauvelous",
        "Maya Blue",
        "Meat Brown",
        "Mellow Apricot",
        "Mellow Yellow",
        "Melon",
        "Midnight Blue",
        "Midnight Green",
        "Mikado Yellow",
        "Mint",
        "Mint Cream",
        "Mint Green",
        "Misty Rose",
        "Moccasin",
        "Mode Beige",
        "Moonstone Blue",
        "Moss Green",
        "Mountain Meadow",
        "Mountbatten Pink",
        "Mulberry",
        "Mustard",
        "Myrtle",
        "Nadeshiko Pink",
        "Napier Green",
        "Naples Yellow",
        "Navajo White",
        "Navy Blue",
        "Neon Carrot",
        "Neon Fuchsia",
        "Neon Green",
        "New York Pink",
        "Non-Photo Blue",
        "North Texas Green",
        "Ocean Boat Blue",
        "Ochre",
        "Office Green",
        "Old Gold",
        "Old Lace",
        "Old Lavender",
        "Old Mauve",
        "Old Rose",
        "Olive",
        "Olive Drab",
        "Olivine",
        "Onyx",
        "Opera Mauve",
        "Orange Peel",
        "Orange-Red",
        "Orange",
        "Orchid",
        "Otter Brown",
        "Ou Crimson Red",
        "Outer Space",
        "Outrageous Orange",
        "Oxford Blue",
        "Pakistan Green",
        "Palatinate Blue",
        "Palatinate Purple",
        "Pale Aqua",
        "Pale Blue",
        "Pale Brown",
        "Pale Carmine",
        "Pale Cerulean",
        "Pale Chestnut",
        "Pale Copper",
        "Pale Cornflower Blue",
        "Pale Gold",
        "Pale Goldenrod",
        "Pale Green",
        "Pale Lavender",
        "Pale Magenta",
        "Pale Pink",
        "Pale Plum",
        "Pale Red-Violet",
        "Pale Robin Egg Blue",
        "Pale Silver",
        "Pale Spring Bud",
        "Pale Taupe",
        "Pale Violet-Red",
        "Pansy Purple",
        "Papaya Whip",
        "Paris Green",
        "Pastel Blue",
        "Pastel Brown",
        "Pastel Gray",
        "Pastel Green",
        "Pastel Magenta",
        "Pastel Orange",
        "Pastel Pink",
        "Pastel Purple",
        "Pastel Red",
        "Pastel Violet",
        "Pastel Yellow",
        "Patriarch",
        "Payne'S Grey",
        "Peach",
        "Peach",
        "Peach-Orange",
        "Peach Puff",
        "Peach-Yellow",
        "Pear",
        "Pearl",
        "Pearl Aqua",
        "Pearly Purple",
        "Peridot",
        "Periwinkle",
        "Persian Blue",
        "Persian Green",
        "Persian Indigo",
        "Persian Orange",
        "Persian Pink",
        "Persian Plum",
        "Persian Red",
        "Persian Rose",
        "Persimmon",
        "Peru",
        "Phlox",
        "Phthalo Blue",
        "Phthalo Green",
        "Piggy Pink",
        "Pine Green",
        "Pink",
        "Pink Lace",
        "Pink-Orange",
        "Pink Pearl",
        "Pink Sherbet",
        "Pistachio",
        "Platinum",
        "Plum",
        "Portland Orange",
        "Powder Blue",
        "Princeton Orange",
        "Prune",
        "Prussian Blue",
        "Psychedelic Purple",
        "Puce",
        "Pumpkin",
        "Purple Heart",
        "Purple",
        "Purple Pizzazz",
        "Purple Taupe",
        "Quartz",
        "Rackley",
        "Radical Red",
        "Rajah",
        "Raspberry",
        "Raspberry Glace",
        "Raspberry Pink",
        "Raspberry Rose",
        "Raw Umber",
        "Razzle Dazzle Rose",
        "Razzmatazz",
        "Red",
        "Red-Brown",
        "Red Devil",
        "Red",
        "Red",
        "Red-Orange",
        "Red",
        "Red",
        "Red-Violet",
        "Redwood",
        "Regalia",
        "Resolution Blue",
        "Rich Black",
        "Rich Brilliant Lavender",
        "Rich Carmine",
        "Rich Electric Blue",
        "Rich Lavender",
        "Rich Lilac",
        "Rich Maroon",
        "Rifle Green",
        "Robin Egg Blue",
        "Rose",
        "Rose Bonbon",
        "Rose Ebony",
        "Rose Gold",
        "Rose Madder",
        "Rose Pink",
        "Rose Quartz",
        "Rose Taupe",
        "Rose Vale",
        "Rosewood",
        "Rosso Corsa",
        "Rosy Brown",
        "Royal Azure",
        "Royal Blue",
        "Royal Fuchsia",
        "Royal Purple",
        "Royal Yellow",
        "Rubine Red",
        "Ruby",
        "Ruby Red",
        "Ruddy",
        "Ruddy Brown",
        "Ruddy Pink",
        "Rufous",
        "Russet",
        "Rust",
        "Rusty Red",
        "Sacramento State Green",
        "Saddle Brown",
        "Safety Orange",
        "Saffron",
        "Salmon",
        "Salmon Pink",
        "Sand",
        "Sand Dune",
        "Sandstorm",
        "Sandy Brown",
        "Sandy Taupe",
        "Sangria",
        "Sap Green",
        "Sapphire",
        "Sapphire Blue",
        "Satin Sheen Gold",
        "Scarlet",
        "Scarlet",
        "School Bus Yellow",
        "Screamin' Green",
        "Sea Blue",
        "Sea Green",
        "Seal Brown",
        "Seashell",
        "Selective Yellow",
        "Sepia",
        "Shadow",
        "Shamrock Green",
        "Shocking Pink",
        "Shocking Pink",
        "Sienna",
        "Silver",
        "Sinopia",
        "Skobeloff",
        "Sky Blue",
        "Sky Magenta",
        "Slate Blue",
        "Slate Gray",
        "Smalt",
        "Smokey Topaz",
        "Smoky Black",
        "Snow",
        "Spiro Disco Ball",
        "Spring Bud",
        "Spring Green",
        "St. Patrick'S Blue",
        "Steel Blue",
        "Stil De Grain Yellow",
        "Stizza",
        "Stormcloud",
        "Straw",
        "Sunglow",
        "Sunset",
        "Tan",
        "Tangelo",
        "Tangerine",
        "Tangerine Yellow",
        "Tango Pink",
        "Taupe",
        "Taupe Gray",
        "Tea Green",
        "Tea Rose",
        "Teal",
        "Teal Blue",
        "Teal Green",
        "Telemagenta",
        "Terra Cotta",
        "Thistle",
        "Thulian Pink",
        "Tickle Me Pink",
        "Tiffany Blue",
        "Tiger'S Eye",
        "Timberwolf",
        "Titanium Yellow",
        "Tomato",
        "Toolbox",
        "Topaz",
        "Tractor Red",
        "Trolley Grey",
        "Tropical Rain Forest",
        "True Blue",
        "Tufts Blue",
        "Tumbleweed",
        "Turkish Rose",
        "Turquoise",
        "Turquoise Blue",
        "Turquoise Green",
        "Tuscan Red",
        "Twilight Lavender",
        "Tyrian Purple",
        "Ua Blue",
        "Ua Red",
        "Ube",
        "Ucla Blue",
        "Ucla Gold",
        "Ufo Green",
        "Ultra Pink",
        "Ultramarine",
        "Ultramarine Blue",
        "Umber",
        "Unbleached Silk",
        "United Nations Blue",
        "University Of California Gold",
        "Unmellow Yellow",
        "Up Forest Green",
        "Up Maroon",
        "Upsdell Red",
        "Urobilin",
        "Usafa Blue",
        "Usc Cardinal",
        "Usc Gold",
        "Utah Crimson",
        "Vanilla",
        "Vegas Gold",
        "Venetian Red",
        "Verdigris",
        "Vermilion",
        "Veronica",
        "Violet",
        "Violet-Blue",
        "Violet",
        "Viridian",
        "Vivid Auburn",
        "Vivid Burgundy",
        "Vivid Cerise",
        "Vivid Tangerine",
        "Vivid Violet",
        "Warm Black",
        "Waterspout",
        "Wenge",
        "Wheat",
        "White",
        "White Smoke",
        "Wild Blue Yonder",
        "Wild Strawberry",
        "Wild Watermelon",
        "Wine",
        "Wine Dregs",
        "Wisteria",
        "Wood Brown",
        "Xanadu",
        "Yale Blue",
        "Yellow",
        "Yellow-Green",
        "Yellow",
        "Yellow Orange",
        "Zaffre",
        "Zinnwaldite Brown",
    ];
}
