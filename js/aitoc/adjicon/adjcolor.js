
/**
 * Visualize Your Attributes - Color Swatch
 *
 * @category:    AdjustWare
 * @package:     AdjustWare_Icon
 * @version      3.1.10
 * @license:     n/a
 * @copyright:   Copyright (c) 2014 AITOC, Inc. (http://www.aitoc.com)
 */
var AdjColor = Class.create({
    initialize: function() {
        this.registry = {
            'charcoal' : '#36454f',
            'black' : '#000000',
            'dim gray' : '#696969',
            'gray' : '#808080',
            'dark gray' : '#A9A9A9',
            'silver' : '#C0C0C0',
            'light grey' : '#D3D3D3',
            'grey' : '#939393',
            'oatmeal' : '#E0DCC8',
            'royal' : '#4169E1',
            'taupe' : '#483C32',
            'gainsboro' : '#DCDCDC',
            'white smoke' : '#F5F5F5',
            'ghost white' : '#F8F8FF',
            'white' : '#FFFFFF',
            'slate gray' : '#708090',
            'light slate gray' : '#778899',
            'midnight blue' : '#191970',
            'navy' : '#000080',
            'dark blue' : '#00008B',
            'dark slate blue' : '#483D8B',
            'medium blue' : '#0000CD',
            'blue' : '#0000FF',
            'steel blue' : '#4682B4',
            'royal blue' : '#4169E1',
            'cornflower blue' : '#6495ED',
            'cornflower' : '#6495ED',
            'dodger blue' : '#1E90FF',
            'deep sky blue' : '#00BFFF',
            'light sky blue' : '#87CEFA',
            'sky blue' : '#87CEEB',
            'light steel blue' : '#B0C4DE',
            'light blue' : '#ADD8E6',
            'powder blue' : '#B0E0E6',
            'pale turquoise' : '#AFEEEE',
            'light cyan' : '#E0FFFF',
            'alice blue' : '#F0F8FF',
            'azure' : '#F0FFFF',
            'mint cream' : '#F5FFFA',
            'dark slate gray' : '#2F4F4F',
            'cadet blue' : '#5F9EA0',
            'teal' : '#008080',
            'dark cyan' : '#008B8B',
            'light sea green' : '#20B2AA',
            'dark turquoise' : '#00CED1',
            'medium turquoise' : '#48D1CC',
            'turquoise' : '#40E0D0',
            'aqua' : '#00FFFF',
            'medium aquamarine' : '#66CDAA',
            'aquamarine' : '#7FFFD4',
            'dark olive green' : '#556B2F',
            'olive' : '#808000',
            'olive drab' : '#6B8E23',
            'dark khaki' : '#BDB76B',
            'dark green' : '#006400',
            'green' : '#008000',
            'forest green' : '#228B22',
            'sea green' : '#2E8B57',
            'medium sea green' : '#3CB371',
            'dark sea green' : '#8FBC8F',
            'medium spring green' : '#00FA9A',
            'spring green' : '#00FF7F',
            'pale green' : '#98FB98',
            'honeydew' : '#F0FFF0',
            'lime green' : '#32CD32',
            'lime' : '#00FF00',
            'light green' : '#90EE90',
            'lawn green' : '#7CFC00',
            'chartreuse' : '#7FFF00',
            'green yellow' : '#ADFF2F',
            'yellow green' : '#9ACD32',
            'indigo' : '#4B0082',
            'purple' : '#800080',
            'dark magenta' : '#8B008B',
            'dark violet' : '#9400D3',
            'dark orchid' : '#9932CC',
            'medium orchid' : '#BA55D3',
            'orchid' : '#DA70D6',
            'violet' : '#EE82EE',
            'plum' : '#DDA0DD',
            'thistle' : '#D8BFD8',
            'blue violet' : '#8A2BE2',
            'medium purple' : '#9370DB',
            'slate blue' : '#6A5ACD',
            'medium slate blue' : '#7B68EE',
            'lavender' : '#FFF0F5',
            'medium violet red' : '#C71585',
            'magenta' : '#FF00FF',
            'deep pink' : '#FF1493',
            'pale violet red' : '#DB7093',
            'hot pink' : '#FF69B4',
            'ligh tpink' : '#FFB6C1',
            'pink' : '#FFC0CB',
            'misty rose' : '#FFE4E1',
            'lavender blush' : '#E6E6FA',
            'maroon' : '#800000',
            'dark red' : '#8B0000',
            'firebrick' : '#B22222',
            'crimson' : '#DC143C',
            'red' : '#FF0000',
            'orange red' : '#FF4500',
            'tomato' : '#FF6347',
            'indian red' : '#CD5C5C',
            'light coral' : '#F08080',
            'salmon' : '#FA8072',
            'dark salmon' : '#E9967A',
            'light salmon' : '#FFA07A',
            'coral' : '#FF7F50',
            'dark orange' : '#FF8C00',
            'orange' : '#FFA500',
            'sandy brown' : '#F4A460',
            'dark goldenrod' : '#B8860B',
            'goldenrod' : '#DAA520',
            'gold' : '#FFD700',
            'yellow' : '#FFFF00',
            'khaki' : '#F0E68C',
            'pale goldenrod' : '#EEE8AA',
            'lemon chiffon' : '#FFFACD',
            'cornsilk' : '#FFF8DC',
            'light goldenrod yellow' : '#FAFAD2',
            'beige' : '#F5F5DC',
            'light yellow' : '#FFFFE0',
            'ivory' : '#FFFFF0',
            'rosy brown' : '#BC8F8F',
            'saddle brown' : '#8B4513',
            'brown' : '#A52A2A',
            'sienna' : '#A0522D',
            'chocolate' :'#D2691E',
            'peru' : '#CD853F',
            'tan' : '#D2B48C',
            'burlywood' : '#DEB887',
            'wheat' : '#F5DEB3',
            'navajo white' : '#FFDEAD',
            'peach puff' : '#FFDAB9',
            'moccasin' : '#FFE4B5',
            'bisque' : '#FFE4C4',
            'blanched almond' : '#FFEBCD',
            'papaya whip' : '#FFEFD5',
            'antique white' : '#FAEBD7',
            'linen' : '#FAF0E6',
            'old lace' : '#FDF5E6',
            'seashell' : '#FFF5EE',
            'floral white' : '#FFFAF0',
            'snow' : '#FFFAFA'
        };
        this.init();
    },

    init: function() {
        var elements = $$("input[class*='adjname']");
        for (var j=0; j<elements.length; j++){
            var names = $w(elements[j].className);
            var result = names.filter(function(item){
                return typeof item == 'string' && item.indexOf("adjname-") > -1;
            });
            var color = result[0].split("-").pop();
            if(this.registry.hasOwnProperty(color) && elements[j].value == '') {
                elements[j].value = this.registry[color];
            }
        }
    }
});