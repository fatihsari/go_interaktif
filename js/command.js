/**
 * Created by Musa ATALAY on 08.05.2014.
 */

function is($var){

    if($var===false||$var==='undefined'||$var===null){

        return false;

    }

    return true;

}

var _call = function(e){

    var _$this = $(e);

    var _command = _$this.attr('command');
    
    $splitCommand = _command.split(' ');

    if($splitCommand.length>1){

        _$this.attr('call', $splitCommand[0]);

        _cmdParams = '';

        for(var $x = 1; $x < $splitCommand.length; $x++){

            $delimiter = ' ';

            if($x == $splitCommand.length){

                $delimiter = '';

            }

            _cmdParams += $splitCommand[$x] + $delimiter;

        }

        _$this.attr('param', _cmdParams);

    }

    var $response = false;

    var $callback = 'void';

    var $parameters = '';

    $callback = _$this.attr('call');

    if(!is($callback)){

        $callback = _$this.attr('func');

        if(!is($callback)){

            $callback = _$this.attr('function');

            if(!is($callback)){

                $callback = _$this.attr('to');

            }

        }

    }

    $params = _$this.attr('param');

    if(!is($params)){

        $params = _$this.attr('parameter');

        if(!is($params)){

            $params = _$this.attr('parameters');

        }

    }

    $splitParameters = $params.split(" ");

    for(var i = 0; i < $splitParameters.length; i++){

        $delimiter = ', ';

        if(i == ($splitParameters.length-1)){

            $delimiter = '';

        }

        if($splitParameters[i] == "this"){

            $parameters += '_$this' + $delimiter;

        }else{

            $parameters += '\'' + $splitParameters[i] + '\'' + $delimiter;

        }

    }

    eval('$response = '+$callback+'('+$parameters+');');

    return $response;

}

var _modal = function(e){
    return true;
}

$(function(){

    var _commands = $('[command]');

    _commands.each(function(){

        var _this = $(this);

        var _command = _this.attr('command');

        $splitCommand = _command.split(' ');

        var $on = '';

        if($splitCommand.length==1){

            $on = _this.attr('on');

        }else{

            $on = $splitCommand[0];

            _command = $splitCommand[1];

            _cmdParams = '';

            for(var $x = 2; $x < $splitCommand.length; $x++){

                $delimiter = ' ';

                if($x == $splitCommand.length){

                    $delimiter = '';

                }

                _cmdParams += $splitCommand[$x] + $delimiter;

            }

            _this.attr('command', _cmdParams);

        }

        _response = null;

        if(is($on)){

            _this.on($on, function(){

                eval('_response = _'+_command+'(_this);');

            });

        }else{

            eval('_response = _'+_command+'(_this);');

        }

    });

    var _events = $('[click], [mouseon], [mouseout], [focus], [blur]');

    _events.each(function(){

        var _this = $(this);

        var _click = {
            _command:   _this.attr('click'),
            _param:     '',
            _response:  null
        };
        var _mouseon = _this.attr('mouseon');
        var _mouseout = _this.attr('mouseout');
        var _focus = _this.attr('focus');
        var _blur = _this.attr('blur');

        if(is(_click._command)){

            $splitClick = _click._command.split(' ');

            _click._command = $splitClick[0];

            if($splitClick.length>1){

                _ClickCmdParams = '';

                for(var $x = 1; $x < $splitClick.length; $x++){

                    $delimiter = ' ';

                    if($x == $splitClick.length){

                        $delimiter = '';

                    }

                    _ClickCmdParams += $splitClick[$x] + $delimiter;

                }

                _click._param = _ClickCmdParams;

            }

            _click._response = null;

            _this.on('click', function(){

                eval('_click._response = _'+_click._command+'(_this);');

            });

        }

    });

});