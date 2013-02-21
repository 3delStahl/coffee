
/*
* Autor: Florian Giller & Leon Bergmann
* Datum: 20.12.2012 12:34 Uhr
* Update: Leon Bergmann - 23.01.2013 18:38 Uhr 
* License: LICENSE.md
*
*	function addList(sort, sourceArray, position = 'listContainer')
*	sort: Gruppenname, sourceArray: Quellarray, position: ID des Parent
*
*/

var zaehler = Array();
var counter = 1;

function ordnung()
{	
	selectSwitch	= document.getElementById('selectSwitch').selectedIndex;
	container		= document.getElementById('chooseContainer');
	selectionTop 	= document.getElementById('selectionTop');
	listContainer 	= document.getElementById('listContainer');
	
	document.getElementById('selectSwitch').disabled = 'true';

	hiddenSwitch			= document.createElement('input');
	hiddenSwitch.type 		= 'hidden';
	hiddenSwitch.name 		= 'switch';
	hiddenSwitch.value 		= selectSwitch;
	container.appendChild(hiddenSwitch);
			
	if(selectSwitch == "1")
	{
		addList('teacher', teacherList, position = 'selectionTop');
		addList('subject', subjectList);
		container.innerHTML 	+= '<input type="button" onclick="addList(\'subject\',subjectList);" value="weiteres Fach">';
	}
	else if(selectSwitch == "2")
	{
		addList('subject',subjectList, position = 'selectionTop');
		addList('teacher', teacherList);	
		container.innerHTML 	+= '<input type="button" onclick="addList(\'teacher\',teacherList);" value="weiterer Lehrer">';
	}

}

function addList(sort, sourceArray, position = 'listContainer')
{
		if(!zaehler[sort]) 
		{
			zaehler[sort] = 0;
		}
			
		obj					= document.createElement('select');
		obj.id 				= 'id_'+sort+zaehler[sort];
		obj.name			= sort+'['+zaehler[sort]+']';
		
		objOut				= document.createElement('p');
		objOut.appendChild(obj);
		document.getElementById(position).appendChild(objOut);
		optionAdd(sourceArray, obj.id);
		zaehler[sort]++;

}

function addListFromExsistingList(dataID, newName, newNamePattern)
{
	var tmp		= document.getElementById(dataID).cloneNode(true);
	tmp.name	= newNamePattern.replace("#", newName);
	return tmp;
}

function createContainer(type, setID, setName = false, content = false, cssClass = false)
{
	var tmp	= document.createElement(type);
	
	if(setName)
	{
		tmp.name	= setName;
		tmp.id		= setID;
	}
	else
	{
		tmp.id		= setID;
		tmp.name	= setID;
	}

	if(content)
	{
		tmp.innerHTML += content;
	}
	
	if(cssClass)
	{
		tmp.className = cssClass;
	}
	
	return tmp;
}

function newRoomChangeField()
{
	// check if we have a couter if not set it to 0
	if(!counter)
	{
		counter = 0;
	}
	// create the HTML Part and the lists
	var form		= document.getElementById('roomPlan');
	var container	= createContainer('div',counter,false,false,'roomPlan');
	var placeholder	= createContainer('span',counter,false,' ------> ');
	var days		= addListFromExsistingList('day[0]', counter,'day[#]');
	var lessons		= addListFromExsistingList('lesson[0]', counter,'lesson[#]');
	var	to			= addRoomList(rooms,"to["+counter+"]",container.id);
	var from		= addRoomList(rooms,"from["+counter+"]",container.id);
	
	// putt all to gether 
	form.appendChild(container);
	container.appendChild(days);
	container.appendChild(lessons);
	container.appendChild(from);
	container.appendChild(placeholder);
	container.appendChild(to);
	// increment the Counter
	counter++;
}


function addRoomList(sourceArray, id)
{
	var obj = createContainer('select',id);
	obj.options[obj.length]	= new Option('-------','0',true);
	for(key in sourceArray)	
	{
		obj.options[obj.length]	= new Option(sourceArray[key]['name'], sourceArray[key]['id']);
	}

	return obj;
}

function safe()
{
	document.getElementById("roomPlan").submit();
}


function optionAdd(array, parrentObjID)
{

	parrentObj = document.getElementById(parrentObjID);
	parrentObj.options[parrentObj.length] = new Option('-------', '0',true);
	for(key in array)
	{
		if(array[key]['vorname'])
			parrentObj.options[parrentObj.length] = new Option(array[key]['vorname']+" "+array[key]['name'], array[key]['id']);
		else
			parrentObj.options[parrentObj.length] = new Option(array[key]['name'], array[key]['id']);
	}

}

function resetList()
{
	if(confirm('Reset?'))
	{
		
	}

	
}

function ergebnisZeigen( selected )
{
	if( selected.length )
	{
		/*
		 * Auswahlstrecke wurde beendet
		 */

		// Visualisierung der Auswahlstrecke
		
		var sel = '';
		var val = '';
		var txt = '';
		for( var i = 0; i < selected.length; i++ )
		{
			txt += ( i>0 ? ' &rarr; ' : '') + selected[i].text;
		}
		var output = '<'+'/p><h4>Texte (Namen) der gew&auml;hlten Optionen<'+'/h4><p>' + txt + '<'+'/p>';
	}
	else
	{
		/*
		 * Auswahlstrecke wurde noch nicht beendet
		 */

		// Hinweis zur Auswahl in der nächsten Auswahlliste
		var output = '<p>W&auml;hlen Sie eine Option aus der n&auml;chsten Auswahlliste.<'+'/p>';
	}
	var ergebnisObj = document.getElementById( 'ergebnis' ).innerHTML = output;
};

window.onload = function()
{
	var vk = new LinkedSelection( [ 'teacher', 'subject'], ergebnisZeigen, auswahl );
	
	parrentObj 	= document.getElementById('teacher');
	liste = auswahl.teacher;
	for(key in liste)
	{
		parrentObj.options[parrentObj.length] = new Option(liste[key],key);
	}
	
}
