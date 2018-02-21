
//var el_new="";
( function( window, $, undefined ) {
	
	var aux = {
	
		getSupportedType	: function() {
			
			var audio = document.createElement( 'audio' );
			
			if ( audio.canPlayType('audio/mpeg;') ) {
				
				return 'mp3';
			
			}
			else if ( audio.canPlayType('audio/ogg;') ) {
			
				return 'ogg';
			
			}
			else {
				
				return 'wav';
			
			}
		
		}
	
	};
	
	// Cassette obj
	$.Cassette 			= function( options, element, songNames ) {
	//window.myOb=this;
		this.$el = $( element );
		//this.songNames = songNames;					
		this._init( options,songNames );
//console.log($( element ));		 		
		//console.log(this.options);	console.log("----");		 		
	};
	
	$.Cassette.defaults 	= {
		// song names. Assumes the path of each song is songs/name.filetype
		songs			: [ '123.mp3', '222.mp3', '333.mp3', 'BlankKyt_RSPN.mp3' ],
		songNames			: [ '--123', '--222', '--333', '--BlankKyt_RSPN' ],
		fallbackMessage	: 'HTML5 audio not supported',
		// initial sound volume
		initialVolume	: 0.7
	};
	
	$.Cassette.prototype 	= {

		_init				: function( options,songNames ) {
			
			var _self = this;   //my
			//if (el_new.length>0) {this.$el=$(el_new);} //my
				
			// the options
			this.options		= $.extend( true, {}, $.Cassette.defaults, options ); //true переписать но оставить не совпадающие
			//this.songNames		= $.extend( true, {}, $.Cassette.defaults, _self.songNames ); //true переписать но оставить не совпадающие
			// current side of the tape
			this.currentSide	= 1;
			// current time of playing side
			this.cntTime		= 0;
			// current sum of the duration of played songs 
			this.timeIterator	= 0;
			// used for rewind / forward
			this.elapsed		= 0.0;
			// action performed
			this.lastaction		= '';
			// if play / forward / rewind active..
			this.isMoving		= false;
			//таймер текущей песни
			this.timerID=0;
			this.$loader		= this.$el.find( 'div.vc-loader' ).show();

			// create cassette sides
			$.when( this._createSides() ).done( function() {
			
				_self.$loader.hide();
			
				// create player
				_self._createPlayer();
				_self.sound = new $.Sound();
				// load events
				_self._loadEvents();
				
			} );
//console.log(this);	
//window.myCar=this;
		},
		_getSide			: function() {

			return ( this.currentSide === 1 ) ? {
				current : this.side1,
				reverse : this.side2	
			} : {
				current : this.side2,
				reverse : this.side1	
			};

		},
		_vyvodSide			: function(elem, side) { // вывести список песен стороны
			var _self 			= this;
			//console.log(_self);
			var curtxt='';
			//var curtime='';
			var spisok=$(elem);
			sider=_self.currentSide;
			if(sider==1){sider=_self.side1;} else {sider=_self.side2;}
			for(var i=0; i<sider.playlist.length;i++){
				if(sider.playlist[i].nameSong.length>58){scrol='marque';} else {scrol='';}
				//console.log(sider.playlist[i].nameSong.length);
				//curtxt=curtxt+'<div class="songblok"><span> * </span><div class="songbar" id="'+sider.playlist[i].id+'">'+sider.playlist[i].nameSong+' <span name="time"></span><div class="progrbar" id="'+i+'"></div></div></div>';
				curtxt=curtxt+'<tr class="songinside" ><td class="songbegin" onclick="beginSong(event);" > '+(i+1)+'. </td><td class="tablbar"><div class="songbar '+scrol+'" id="'+sider.playlist[i].id+'"><span class="namescroll">'+sider.playlist[i].nameSong+'</span> <span name="time" style=""></span></div><div class="progrbar" id="'+i+'"></div></td>';
			}	
			spisok.html('<table class="songblok3"><tr class="songup" ><td></td></tr></table>'+'<table class="songblok">'+curtxt+'</table>' +'<table class="songblok2"><tr class="songdown" ><td></td></tr></table>');
			//console.log(sider.playlist.length);
			 marqueRun();
		},
		// songs are distributed equally on both sides
		_createSides		: function() {
		
			var playlistSide1 	= [],
				playlistSide2 	= [],
				_self 			= this, 
				cnt 			= 0;
				
			return $.Deferred(
			
				function( dfd ) {
				
					for( var i = 0, len = _self.options.songs.length; i < len; ++i ) {
						
						var song = new $.Song( _self.options.songs[i], i, _self.options.songNames[i]);
						//console.log(song);
						$.when( song.loadMetadata() ).done( function( song ) { // здесь происходит разделение по сторонам. Сделать функцию расчета времени добавть префикс A_ B_
							( song.id < len / 2 ) ? playlistSide1.push( song ) : playlistSide2.push( song );
							
							++cnt;
							
							if( cnt === len ) {
								
								// two sides, each side with multiple songs
								_self.side1 	= new $.Side( 'side1', playlistSide1, 'start' ),
								_self.side2		= new $.Side( 'side2', playlistSide2, 'end' );

								_self._vyvodSide('.spisokside',1);
								dfd.resolve();
							
							}
							
						} );
						
					}
			
				}
				
			).promise();
			
		},
        // songs are distributed equally on both sides
        _createOneSides		: function(sides) {

            var playlistSide1 	= [],
                playlistSide2 	= [],
                _self 			= this,
                cnt 			= 0;

            return $.Deferred(

                function( dfd ) {

                    for( var i = 0, len = _self.options.songs.length; i < len; ++i ) {
                        var song = new $.Song( _self.options.songs[i], i, _self.options.songNames[i], sides[i]);

                        //console.log(len);
                        $.when( song.loadMetadata() ).done( function( song ) { // здесь происходит разделение по сторонам. Сделать функцию расчета времени добавть префикс A_ B_
							if(song.side === 'side1'){
                                playlistSide1.push( song );
							} else {
                                playlistSide2.push( song );
							}
                            //( song.id < len / 2 ) ? playlistSide1.push( song ) : playlistSide2.push( song );

                            ++cnt;

                            if( cnt === len ) {

                                // two sides, each side with multiple songs

                                    _self.side1 = new $.Side('side1', playlistSide1, 'start');

                                    _self.side2 = new $.Side('side2', playlistSide2, 'end');


                                _self._vyvodSide('.spisokside',1);
                                dfd.resolve();

                            }

                        } );

                    }

                }

            ).promise();

        },
		_createPlayer		: function() {
			
			// create HTML5 audio element
			this.$audioEl	= $( '<audio id="audioElem"><span>' + this.options.fallbackMessage + '</span></audio>' );
			this.$el.prepend( this.$audioEl );
			
			this.audio		= this.$audioEl.get(0);

			// create custom controls
			this._createControls();

			this.$theTape	= this.$el.find( 'div.vc-tape' );
			this.$wheelLeft	= this.$theTape.find( 'div.vc-tape-wheel-left' );
			this.$wheelRight= this.$theTape.find( 'div.vc-tape-wheel-right' );
			this.$tapeSideA	= this.$theTape.find( 'div.vc-tape-side-a' ).show();
			this.$tapeSideB	= this.$theTape.find( 'div.vc-tape-side-b' );
			
		},
		_createControls		: function() {

			var _self		= this;
			
			this.$controls 	= $( '<ul class="vc-controls" style="display:none;"/>' );
			
			this.$cPlay		= $( '<li class="vc-control-play">Play<span></span></li>' );
			this.$cRewind	= $( '<li class="vc-control-rewind">Rew<span></span></li>' );
			this.$cForward	= $( '<li class="vc-control-fforward">FF<span></span></li>' );
			this.$cStop		= $( '<li class="vc-control-stop">Stop<span></span></li>' );
			this.$cStop22		= $( '#stopvot' );
			this.$cSwitch	= $( '<li class="vc-control-switch">Switch<span></span></li>' );
			
			this.$controls.append( this.$cPlay )
						  .append( this.$cRewind )
						  .append( this.$cForward )
						  .append( this.$cStop )
						  .append( this.$cSwitch )
						  .appendTo( this.$el );

			this.$volume 	= $( '<div style="display:none;" class="vc-volume-wrap"><div class="vc-volume-control"><div class="vc-volume-knob"></div></div></div> ').appendTo( this.$el );

			if (document.createElement('audio').canPlayType) {

				if (!document.createElement('audio').canPlayType('audio/mpeg') && !document.createElement('audio').canPlayType('audio/ogg')) {

					// TODO: flash fallback!
				
				} 
				else {

					this.$controls.show();
					this.$volume.show();
					this.$volume.find( 'div.vc-volume-knob' ).knobKnob({
						snap : 10,
						value: 359 * this.options.initialVolume,
						turn : function( ratio ) {
							
							_self._changeVolume( ratio );
						
						}
					});

					this.audio.volume = this.options.initialVolume;
				}

			}
			
		},
		_loadLoud : function (){  ////myyyyyyyyyy
			var _self		= this;
			//var volcurrent=$( '<div style="display:none;" class="vc-volume-wrap"><div class="vc-volume-control"><div class="vc-volume-knob"></div></div></div> ');
			//this.$volume
			$('.vc-volume-wrap').remove();
			this.$volume 	= $( '<div style="display:none;" class="vc-volume-wrap"><div class="vc-volume-control"><div class="vc-volume-knob"></div></div></div> ').appendTo( this.$el );
			this.$volume.show();
			//return false;
			this.$volume.find( 'div.vc-volume-knob' ).knobKnob({
						snap : 10,
						value: 359 * this.options.initialVolume,
						turn : function( ratio ) {
							
							_self._changeVolume( ratio );
						
						}
					});
			this.audio.volume = this.options.initialVolume;		
			},
		
		_progrLoad : function (param){  ////myyyyyyyyyy
		var _self = this;
		var Sider=_self.currentSide; //сторона кассеты
		var msSide=_self._getSide().current; //id: "side2", status: "middle", playlist: Array[2], duration: 433.781932, playlistCount: 2 } playlistCount список песен
		var tek_vrem=_self.cntTime; // текущее время на стороне кассеты
		var msTekPos=_self._getSongInfoByTime(tek_vrem); //  текущая песня/Object { songIdx: 0, timeInSong: 13.882392, iterator: 0 } iterator - сумма время предыдущих песен   
		var dlinSong=0;
		var dlinBlok=$('.songbar').css('width');  //console.log(parseInt(dlinBlok,10)); 
		var odinPrBlok=parseInt(dlinBlok,10)/100;
		var tekSong=function(){ dlinSong=msSide.playlist[msTekPos.songIdx].duration;return (dlinSong/100);};
		
		var vremSek=function(seconds){var m=Math.floor(seconds/60)<10?"0"+Math.floor(seconds/60):Math.floor(seconds/60);
				var s=Math.floor(seconds-(m*60))<10?"0"+Math.floor(seconds-(m*60)):Math.floor(seconds-(m*60));
				return m+":"+s;};
		if(param==1) { _self.timerID = setInterval(function() { 
		msTekPos=_self._getSongInfoByTime(_self.cntTime);
		tekPrSong=msTekPos.timeInSong/tekSong()*odinPrBlok;
		$('.progrbar').each(function(index, element) { if($(element).attr('id')==msTekPos.songIdx) {$(element).css('width',tekPrSong+'px');
		//$('.marque').simplemarquee._reset();
		if($(element).siblings('.marque').length>0){
		pr=$(element).siblings('.marque');
		if (pr.children('.simplemarquee-wrapper').length>0){} else{pr.data()._simplemarquee.update(true);}
		}
		//console.log(pr);
		//console.log($(element).parent().find('span[name="time"]').html());
		$(element).parent().find('span[name="time"]').html(' ('+vremSek(msTekPos.timeInSong)+'/'+vremSek(dlinSong)+')');} //.css('display','block')
		else {$(element).css('width','0px');$(element).parent().find('span[name="time"]').html('');}            
        });		
		 //console.log(tekPrSong);
		 }, 1000);
		}
		if(param==0) { setTimeout(function() {  clearInterval( _self.timerID);}, 0);}
		
		},
		
		_loadEvents			: function() {
			
			var _self = this;
			
			this.$cSwitch.on( 'mousedown', function( event ) {
				
				_self._setButtonActive( $( this ) );
				_self._switchSides();
				
			} );
			
			this.$cPlay.on( 'mousedown', function( event ) {
				$.when( _self.sound.play( 'click' ) ).done( function() {// здесь клик кнопки играть
					_self._setButtonActive( $( this ) );
					_self._play();
					_self._progrLoad(1);
					});
				
				
			} );
			
			this.$cStop.on( 'mousedown', function( event ) {
				
				_self._setButtonActive( $( this ) );
				_self._stop();
				_self._progrLoad(0);
				

			} );
			
			this.$cStop22.on( 'mousedown', function( event ) { //------------------------------------------ myyyyyyyyy
				
				_self._setButtonActive( $( this ) );
				_self._stop();

			} );
			
			

			this.$cForward.on( 'mousedown', function( event ) {
				
				_self._setButtonActive( $( this ) );
				_self._forward();
				_self._progrLoad(0);
			
			} );

			this.$cRewind.on( 'mousedown', function( event ) {
				
				_self._setButtonActive( $( this ) );				
				_self._rewind();
				_self._progrLoad(0);
			
			} );
			
			this.$audioEl.on( 'timeupdate', function( event ) {
			
				_self.cntTime	= _self.timeIterator + _self.audio.currentTime;
				var wheelVal	= _self._getWheelValues( _self.cntTime );
				_self._updateWheelValue( wheelVal );

			});
			
			this.$audioEl.on( 'loadedmetadata', function( event ) {
				
			});

			this.$audioEl.on( 'ended', function( event ) {
				
				_self.timeIterator += _self.audio.duration;
				_self._play();
				
			});
		
		},
		_setButtonActive	: function( $button ) {

			// TODO. Solve! For now:
			$button.addClass( 'vc-control-pressed' );

			setTimeout( function() {
				
				$button.removeClass( 'vc-control-pressed' );	
				
			}, 100 );

		},	
		_updateAction		: function( action ) {

			this.lastaction = action;

		},
		_prepare			: function( song ) {
		
			this._clear();
			this.$audioEl.attr( 'src', song.getSource( aux.getSupportedType() ) );
			
		},
		_switchSides		: function() {

			if( this.isMoving ) {

				alert( 'Пожалуйста, остановите плеер перед переключением на другую сторону!.' );
				return false;

			}
			
			this.sound.play( 'switch' );

			var _self = this;

			this.lastaction = '';

			if( this.currentSide === 1 ) {

				this.currentSide = 2;

				this.$theTape.css( {
					'-webkit-transform'	: 'rotate3d(0, 1, 0, 180deg)',
					'-moz-transform'	: 'rotate3d(0, 1, 0, 180deg)',
					'-o-transform'		: 'rotate3d(0, 1, 0, 180deg)',
					'-ms-transform'		: 'rotate3d(0, 1, 0, 180deg)',
					'transform'			: 'rotate3d(0, 1, 0, 180deg)'
				} );

				setTimeout( function() {

					_self.$tapeSideA.hide();
					_self.$tapeSideB.show();

					// update wheels
					_self.cntTime = _self._getPosTime();

				}, 200 );

			} 
			else {

				this.currentSide = 1;

				this.$theTape.css( {
					'-webkit-transform'	: 'rotate3d(0, 1, 0, 0deg)',
					'-moz-transform'	: 'rotate3d(0, 1, 0, 0deg)',
					'-o-transform'		: 'rotate3d(0, 1, 0, 0deg)',
					'-ms-transform'		: 'rotate3d(0, 1, 0, 0deg)',
					'transform'			: 'rotate3d(0, 1, 0, 0deg)'
				} );

				setTimeout( function() {

					_self.$tapeSideB.hide();
					_self.$tapeSideA.show();

					// update wheels
					_self.cntTime = _self._getPosTime();
					
				}, 200 );

			}
		_self._vyvodSide('.spisokside',1);	
		},
		_switchSidesA		: function(storona) {  //включить первую сторону -----------------------------------------------
		var _self=this;
		
		if( this.isMoving ) {

				alert( 'Пожалуйста, остановите плеер перед сменой кассеты!.' );
				return false;

			}
		
		this.lastaction = '';
		_self.cntTime = 0;// alert();
		if (storona==2){	
			this.currentSide = 1;  // здесь сделать подмену кртинки
			
			this.$theTape.css( {
					'-webkit-transform'	: 'rotate3d(0, 1, 0, 0deg)',
					'-moz-transform'	: 'rotate3d(0, 1, 0, 0deg)',
					'-o-transform'		: 'rotate3d(0, 1, 0, 0deg)',
					'-ms-transform'		: 'rotate3d(0, 1, 0, 0deg)',
					'transform'			: 'rotate3d(0, 1, 0, 0deg)'
				} );

				setTimeout( function() {

					_self.$tapeSideB.hide();
					_self.$tapeSideA.show();

					// update wheels
					_self.cntTime = 0;//_self._getPosTime();
					 wheelVal2	= _self._getWheelValues( _self.cntTime );
				_self._updateWheelValue( wheelVal2 );
					//this._setSidesPosStatus( 'end' );
					}, 200 );
			//this._getSide().reverse.setPositionStatus( 'start' );	
		} else {
			setTimeout( function() {
			self.cntTime = 0;//_self._getPosTime();
			 wheelVal2	= _self._getWheelValues( _self.cntTime );
			_self._updateWheelValue( wheelVal2 );
			
			}, 200 );
			}
			
			},
		
		_updateButtons		: function( button ) {

			var pressedClass = 'vc-control-active';

			this.$cPlay.removeClass( pressedClass );
			this.$cStop.removeClass( pressedClass );
			this.$cRewind.removeClass( pressedClass );
			this.$cForward.removeClass( pressedClass );

			switch( button ) {

				case 'play'		: this.$cPlay.addClass( pressedClass ); break;
				case 'rewind'	: this.$cRewind.addClass( pressedClass ); break;
				case 'forward'	: this.$cForward.addClass( pressedClass ); break;

			}

		},
		_changeVolume		: function( ratio ) {

			this.audio.volume = ratio;
			
		},
		_play				: function() {

			var _self	= this;

			this._updateButtons( 'play' );

			//$.when( this.sound.play( 'click' ) ).done( function() {
			$.when( 1 ).done( function() {	// звук от этой фигни между песнями

				var data	= _self._updateStatus();

			if( data ) {

					_self._prepare( _self._getSide().current.getSong( data.songIdx ) );
				
					_self.$audioEl.on( 'canplay', function( event ) {

					$( this ).off( 'canplay' );
					
					_self.audio.currentTime = data.timeInSong;
					_self.audio.play();
					_self.isMoving = true;

					_self._setWheelAnimation( '2s', 'play' );
					
				});

			}
		
			} );

		},
		_updateStatus		: function( buttons ) {

			var posTime	= this.cntTime;

			// first stop
			this._stop( true );

			this._setSidesPosStatus( 'middle' );

			// the current time to play is this.cntTime +/- [this.elapsed]
			if( this.lastaction === 'forward' ) {

				posTime += this.elapsed;

			}
			else if( this.lastaction === 'rewind' ) {

				posTime -= this.elapsed;

			}

			// check if we have more songs to play on the current side..
			if( posTime >= this._getSide().current.getDuration() ) {

				this._stop( buttons );
				this._setSidesPosStatus( 'end' );
				return false;

			}

			this._resetElapsed();

			// given this, we need to know which song is playing at this point in time,
			// and from which point in time within the song we will play
			var data			= this._getSongInfoByTime( posTime );

			// update cntTime
			this.cntTime		= posTime;
			// update timeIterator
			this.timeIterator	= data.iterator;

			return data;

		},
		_rewind				: function() {

			var _self	= this,
				action 	= 'rewind';

			if( this._getSide().current.getPositionStatus() === 'start' ) {

				return false;
				
			}

			this._updateButtons( action );

			$.when( this.sound.play( 'click' ) ).done( function() {

				_self._updateStatus( true );
				_self.isMoving = true;

				_self._updateAction( action );
				_self.sound.play( 'rewind', true );
				_self._setWheelAnimation( '0.5s', action );
				_self._timer();

			} );
			
		},
		_forward			: function() {

			var _self	= this,
				action 	= 'forward';

			if( this._getSide().current.getPositionStatus() === 'end' ) {

				return false;

			}

			this._updateButtons( action );

			$.when( this.sound.play( 'click' ) ).done( function() {
			
				_self._updateStatus( true );
				_self.isMoving = true;
			
				_self._updateAction( action );
				_self.sound.play( 'fforward', true );
				_self._setWheelAnimation( '0.5s', action );
				_self._timer();

			} );

		},
		_stop				: function( buttons ) {
			
			if( !buttons ) { 

				this._updateButtons( 'stop' );
				this.sound.play( 'click' );

			}
			this.isMoving = false;
			this._stopWheels();
			this.audio.pause();
			this._stopTimer();
		
		},
		_clear				: function() {
			
			this.$audioEl.children( 'source' ).remove();
		
		},
		_setSidesPosStatus	: function( position ) {

			this._getSide().current.setPositionStatus( position );

			switch( position ) {

				case 'middle'	: this._getSide().reverse.setPositionStatus( position );break;
				case 'start'	: this._getSide().reverse.setPositionStatus( 'end' );break;
				case 'end'		: this._getSide().reverse.setPositionStatus( 'start' );break;

			}

		},
		// given a point in time for the current side, returns the respective song of that side and the respective time within the song
		_getSongInfoByTime	: function( time ) {

			var data		= { songIdx : 0, timeInSong : 0 },
				side		= this._getSide().current,
				playlist	= side.getPlaylist(),
				len			= side.getPlaylistCount(),
				cntTime		= 0;

			for( var i = 0; i < len; ++i ) {

				var song		= playlist[ i ],
					duration	= song.getDuration();

				cntTime += duration;

				if( cntTime > time ) {

					data.songIdx	= i;
					data.timeInSong	= time - ( cntTime - duration );
					data.iterator	= cntTime - duration;

					return data;

				}

			}

			return data;

		},
		_getWheelValues		: function( x ) {

			var T	= this._getSide().current.getDuration(),
				val = {
					left	: ( this.currentSide === 1 ) ? ( -60 / T ) * x + 60 : ( 60 / T ) * x,
					right	: ( this.currentSide === 1 ) ?  ( 60 / T ) * x : ( -60 / T ) * x + 60
				};
			return val;

		},
		_getPosTime			: function() {

			var wleft	= this.$wheelLeft.data( 'wheel' ),
				wright	= this.$wheelRight.data( 'wheel' );

			if( wleft === undefined ) {

				wleft = 60;

			}
			if( wright === undefined ) {

				wright = 0;
				
			}
			
			var T		= this._getSide().current.getDuration(),
				posTime	= this.currentSide === 2 ? ( T * wleft ) / 60 : ( T * wright ) / 60;

			return posTime;

		},
		_updateWheelValue	: function( wheelVal ) {

			this.$wheelLeft.data( 'wheel', wheelVal.left ).css( { 'box-shadow' : '0 0 0 ' + wheelVal.left + 'px black' } );
			this.$wheelRight.data( 'wheel', wheelVal.right ).css( { 'box-shadow' : '0 0 0 ' + wheelVal.right + 'px black' } );

		},
		_setWheelAnimation	: function( speed, mode ) {

			var _self = this, anim 	= '';

			switch( this.currentSide ) {

				case 1 :
					if( mode === 'play' || mode === 'forward' ) {
						anim = 'rotateLeft';
					}
					else if( mode === 'rewind' ) {
						anim = 'rotateRight';
					}
					break;
				case 2 :
					if( mode === 'play' || mode === 'forward' ) {
						anim = 'rotateRight';
					}
					else if( mode === 'rewind' ) {
						anim = 'rotateLeft';
					}
					break;

			}

			var animStyle = { 
				'-webkit-animation'	: anim + ' ' + speed + ' linear infinite forwards',
				'-moz-animation'	: anim + ' ' + speed + ' linear infinite forwards',
				'-o-animation'		: anim + ' ' + speed + ' linear infinite forwards',
				'-ms-animation'		: anim + ' ' + speed + ' linear infinite forwards',
				'animation'			: anim + ' ' + speed + ' linear infinite forwards'
			};

			setTimeout( function() {

				_self.$wheelLeft.css(animStyle);
				_self.$wheelRight.css(animStyle);

			}, 0 );			

		},
		_stopWheels			: function() {

			var wheelStyle = {
				'-webkit-animation'	: 'none',
				'-moz-animation'	: 'none',
				'-o-animation'		: 'none',
				'-ms-animation'		: 'none',
				'animation'			: 'none'
			}

			this.$wheelLeft.css( wheelStyle );
			this.$wheelRight.css( wheelStyle );

		},
		// credits
		_timer				: function() {

			var _self	= this,
				start	= new Date().getTime(),  
				time	= 0;

			this._resetElapsed();

			this.isSeeking = true;
			
			this._setSidesPosStatus( 'middle' );
			
			if( this.isSeeking ) {
			
				clearTimeout( this.timertimeout );
				this.timertimeout = setTimeout( function() {

					_self._timerinstance( start, time );

				}, 100 );
			
			}

		},
		_timerinstance		: function( start, time ) {

			var _self = this;	

			time += 100;  
			
			this.elapsed = Math.floor(time / 20) / 10;  
			
			if( Math.round( this.elapsed ) == this.elapsed ) { 

				this.elapsed += 0.0;

			}  
			
			// stop if it reaches the end of the cassette / side
			// or if it reaches the beginning
			var posTime = this.cntTime;

			if( this.lastaction === 'forward' ) {

				posTime += this.elapsed;

			}
			else if( this.lastaction === 'rewind' ) {

				posTime -= this.elapsed;

			}

			var wheelVal = this._getWheelValues( posTime );
			this._updateWheelValue( wheelVal );

			if( posTime >= this._getSide().current.getDuration() || posTime <= 0 ) {

				this._stop();
				( posTime <= 0) ? this.cntTime = 0 : this.cntTime = posTime;
				this._resetElapsed();
				( posTime <= 0) ? this._setSidesPosStatus( 'start' ) : this._setSidesPosStatus( 'end' );
				return false;

			}

			var diff = (new Date().getTime() - start) - time;  
			
			if( this.isSeeking ) {

				clearTimeout( this.timertimeout );
				this.timertimeout = setTimeout( function() {

					_self._timerinstance( start, time );

				}, ( 100 - diff ) );

			}

		},
		_stopTimer			: function() {

			clearTimeout( this.timertimeout );
			this.isSeeking = false;

		},
		_resetElapsed		: function() {

			this.elapsed = 0.0;

		}

	};
	
	// Cassette side obj
	$.Side					= function( id, playlist, status ) {
	
		// side's name / id
		this.id 		= id;
		// status is "start", "middle" or "end"
		this.status		= status;
		// array of songs sorted by song id
		this.playlist	= playlist.sort( function( a, b ) {
			
			var aid = a.id,
				bid	= b.id;
			
			return ( ( aid < bid ) ? -1 : ( ( aid > bid ) ? 1 : 0 ) );
			
		} );
		// set playlist duration
		this._setDuration();
		// total number of songs
		this.playlistCount	= playlist.length;
		
	};
	
	$.Side.prototype 		= {

		getSong				: function( num ) {
		
			return this.playlist[ num ];
		
		},
		getPlaylist			: function() {
			
			return this.playlist;
		
		},
		_setDuration		: function() {
		
			this.duration = 0;
			
			for( var i = 0, len = this.playlist.length; i < len; ++i ) {
			
				this.duration += this.playlist[ i ].duration;
			
			}
		
		},
		getDuration			: function() {
			
			return this.duration;
		
		},
		getPlaylistCount	: function() {
		
			return this.playlistCount;
		
		},
		setPositionStatus	: function( status ) {

			this.status = status;

		},
		getPositionStatus	: function() {

			return this.status;

		}

	};
	
	// Song obj
	$.Song 					= function( name, id, nameSong ) {
		
		this.id		= id;
		this.name 	= name;
		this.nameSong 	= nameSong;
		if(arguments[3]){this.side=arguments[3]; }
		this._init();
		
	};
	
	$.Song.prototype 		= {

		_init				: function() {
		
			this.sources	= {
				mp3	: '/catalog/punkts/' + this.name + '', //'.mp3'
				ogg	: '/catalog/punkts/' + this.name + '' //'.ogg'
			};
		
		},
		getSource			: function( type ) {
			
			return this.sources[type];
		
		},
		// load metadata to get the duration of the song
		loadMetadata		: function() {
			
			var _self = this;
			
			return $.Deferred(
			
				function( dfd ) {
					
					var $tmpAudio 	= $( '<audio/>' ),
						songsrc		= _self.getSource( aux.getSupportedType() );
                    songsrc3=songsrc;
                    songsrc2=songsrc.replace('&amp;','&');
					$tmpAudio.attr( 'preload', 'auto' );
					$tmpAudio.attr( 'src', songsrc2 );
                  //  console.log($tmpAudio);
					$tmpAudio.on( 'loadedmetadata', function( event ) {

						_self.duration = $tmpAudio.get(0).duration;
//console.log(_self.duration);console.log(_self);
						dfd.resolve( _self );
						
					});

				}
				
			).promise();
		
		},
		getDuration			: function() {
		
			return this.duration;
		
		}

	};
	
	// Sound obj
	$.Sound					= function() {
		
		this._init();
		
	};
	
	$.Sound.prototype		= {

		_init				: function() {

			this.$audio	= $( '<audio/>' ).attr( 'preload', 'auto' );
		
		},
		getSource			: function( type ) {

			return '/sounds/' + this.action + '.' + type;
		
		},
		play				: function( action, loop ) {
			
			var _self = this;
			return $.Deferred( function( dfd ) {

				_self.action = action;

				var soundsrc = _self.getSource( aux.getSupportedType() );
				
				_self.$audio.attr( 'src', soundsrc );
				if( loop ) {

					_self.$audio.attr( 'loop', loop );

				}
				else {

					_self.$audio.removeAttr( 'loop' );

				}
					
				_self.$audio.on( 'canplay', function( event ) {
					
					// TODO: change timeout to ended event . ended event does not trigger for safari ?
					setTimeout( function() {

						dfd.resolve();

					}, 500 );
					$( this ).get(0).play();
					
				});

			});
		
		}

	};
	
	var logError 			= function( message ) {

		if ( window.console ) {

			window.console.error( message );
		
		}

	};
	
	$.fn.cassette			= function( options ) {
		
		if ( typeof options === 'string' ) {
			
			var args = Array.prototype.slice.call( arguments, 1 );
			
			this.each(function() {
			
				var instance = $.data( this, 'cassette' );
				
				if ( !instance ) {

					logError( "cannot call methods on cassette prior to initialization; " +
					"attempted to call method '" + options + "'" );
					return;
				
				}
				
				if ( !$.isFunction( instance[options] ) || options.charAt(0) === "_" ) {

					logError( "no such method '" + options + "' for cassette instance" );
					return;
				
				}
				
				instance[ options ].apply( instance, args );
			
			});
		
		} 
		else {
		
			this.each(function() {
			
				var instance = $.data( this, 'cassette' );
				if ( !instance ) {
					$.data( this, 'cassette', new $.Cassette( options, this ) );
				}
			});
		
		}
		
		return this;
		
	};
//window.trans=$.Cassette;
//window.tranpr=$.Cassette.prototype;
//console.log($.Cassette.options);	
} )( window, jQuery );