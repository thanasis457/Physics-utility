function tokenise_eq(str){
  var final='';
  var cur='';
  var check=false;
  str+=' ';
  for(i=0; i<str.length; i++){
    if(str[i]==' '){
      if(check==true){
        if(final=='') final=cur;
        else final+=' '+cur;
      }
      // console.log(final);
      // console.log(check);
      cur='';
      check=false;
      continue;
    }
    if(str[i]=='=') check=true;
    cur+=str[i];
  }
  // console.log(final);
  cur+=str[0];
  // console.log(str.length);
  return final;
}
// console.log('Result: '+tokenise_eq("Car is moving with u=10m/s and accelerate at a=10m/s^2"));

function tokenise(str){
  var final=[];
  var cur='';
  str+=' ';
  for(i=0; i<str.length; i++){
    if(str[i]==' '){
      final.push(cur);
      cur='';
      continue;
    }
    cur+=str[i];
  }
  // console.log(final);
  return final;
}
function dictionary_tok(str){
  var dict={
    'velocity': 'u',
    'ending velocity': 'fu',
    'starting velocity': 'su',
    'acceleration': 'a',
    'accelerates':'a',
    'accelerate':'a',
    'force': 'f',
    'total force': 'sf',
    'distance': 'dx',
    'position': 'x',
    'starting position': 'sx', //starting x
    'ending position': 'fx', //finishing x
    'time': 't',
    'starting time': 'st',
    'ending time': 'et'
  }
  var list=tokenise(str);
  var final=[];
  var check=new Array(16);
  check.fill(false);
  // console.log(check);
  for(i=0; i<list.length-1; i++){
    if(!check[i] && !check[i+1]){
      var future=list[i]+' '+list[i+1];
      // console.log(future);
      if(dict[future]!=null){
        final.push(dict[future]);
        check[i]=check[i+1]=true;
      }
    }
    if(!check[i]){
      // console.log(list[i]);
      if(dict[list[i]]!=null){
        final.push(dict[list[i]]);
        check[i]=true;
      }
    }
  }
  if(!check[list.length-1] && dict[list[list.length-1]]!=null){ //last case
    final.push(dict[list[list.length-1]]);
  }
  var unexplored=0;
  for(i=0; i<final.length; i++){
    if(check[i]) continue;
    var resnum='';
    var type='';
    if(list[i].charCodeAt(0)>=49 && list[i].charCodeAt(0)<=57){
      var p=0;
      while(list[i].charCodeAt(p)>=49 && list[i].charCodeAt(p)<=57){
        resnum+=list[i][p];
        p++;
      }
      while(p<list[i].length()){
        type+=list[i][p];
        p++;
      }
    }
    Parse first object as final[unexplored],resnum,type, like: u, 10, m/s
    in three different json objectsx
    unexplored++;
  }
  // console.log(final);
}
for(int i=0; i<N; i++)
console.log(dictionary_tok("Car is moving with velocity of 10m/s and accelerates at 10m/s^2"));

function distinguish(str){
  for(int i=0; i<N)
}
