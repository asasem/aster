/**
 * Created with JetBrains PhpStorm.
 * User: Андрей
 * Date: 26.05.14
 * Time: 15:36
 * To change this template use File | Settings | File Templates.
 */

function getPlayer(pid) {
    var obj = document.getElementById(pid);
    if (obj.doPlay) return obj;
    for(i=0; i<obj.childNodes.length; i++) {
    var child = obj.childNodes[i];
    if (child.tagName == "EMBED") return child;
    }
}
function doPlay(fname) {
    var player = getPlayer('haxe');
    player.doPlay(fname);

    }
function doStop() {
    var player = getPlayer('haxe');
    player.doStop();

    }
function doPause() {
    var player = getPlayer('haxe');
    player.doPause();
}

function doResume() {
    var player = getPlayer('haxe');
    player.doResume();
}

function setVolume(v) {
    var player = getPlayer('haxe');
    player.setVolume(v);
    }
function setPan(p) {
    var player = getPlayer('haxe');
    player.setPan(p);
    }
var SoundLen = 0;
var SoundPos = 0;
var Last = undefined;
var State = "ОСТАНОВЛЕН";
var Timer = undefined;
var PLAY=false;

function getPerc(a, b) {
    return ((b==0?0.0:a/b)*100).toFixed(2);
    }
function FileLoad(bytesLoad, bytesTotal) {
    document.getElementById('InfoFile').innerHTML = "ЗАГРУЖЕНО "+bytesLoad+"/"+bytesTotal+" байт ("+getPerc(BytesLoad,BytesTotal)+"%)";
    }
function SoundLoad(secLoad, secTotal) {
   // document.getElementById('InfoSound').innerHTML = "Available "+secLoad.toFixed(2)+"/"+secTotal.toFixed(2)+" seconds ("+getPerc(secLoad,secTotal)+"%)";
    SoundLen = secTotal;
    }
var InfoState = undefined;
function Inform() {
    if (Last != undefined) {
    var now = new Date();
    var interval = (now.getTime()-Last.getTime())/1000;
    SoundPos += interval;
    Last = now;
    }
InfoState.innerHTML = State +"<br />"+" ("+SoundPos.toFixed(2)+"/"+SoundLen.toFixed(2)+") сек ("+getPerc(SoundPos,SoundLen)+"%)";
}
function SoundState(state, position) {
    if (position != undefined) SoundPos = position;
    if (State != "ВОСПРОИЗВЕДЕНИЕ" && state=="ВОСПРОИЗВЕДЕНИЕ") {
    Last = new Date();
    Timer = setInterval(Inform, 100);
    Inform();
        PLAY=false;
    } else
if (State == "ВОСПРОИЗВЕДЕНИЕ" && state!="ВОСПРОИЗВЕДЕНИЕ") {
    clearInterval(Timer);
    Timer = undefined;
    Inform();
    PLAY=true;
    }
State = state;
Inform();
}
function init() {
    var player = getPlayer('haxe');
    if (!player || !player.attachHandler) setTimeout(init, 100); // Wait for load
    else {
    player.attachHandler("progress", "FileLoad");
    player.attachHandler("PLAYER_LOAD", "SoundLoad");
    player.attachHandler("PLAYER_BUFFERING", "SoundState", "БУФЕРИЗАЦИЯ");
    player.attachHandler("PLAYER_PLAYING", "SoundState", "ВОСПРОИЗВЕДЕНИЕ");
    player.attachHandler("PLAYER_STOPPED", "SoundState", "ОСТАНОВЛЕН");
    player.attachHandler("PLAYER_PAUSED", "SoundState", "ПАУЗА");
   InfoState = document.getElementById('InfoState')

    Inform();
    }
}

