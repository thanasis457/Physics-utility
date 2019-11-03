from vpython import*
import time
#from gtts import gTTS
import os
import speech_recognition as sr
#tts = gTTS(text='Good morning', lang='en')
mic=sr.Microphone()
r=sr.Recognizer()
lang="en-US"
def rec(state):
    if(state==1):
        with mic as source:
            #tts=gTTS(text="Initialising",lang="en")
            print("Adjusting for ambient noise\n")
            r.adjust_for_ambient_noise(source)
            #tts=gTTS(text="Listening",lang="en")
            print("Listening\n")
            audio=r.listen(source)
            try:
                text=r.recognize_google(audio,language=lang)
                return text
            except:
                #tts=gTTS(text="Voice could not be recognised",lang="en")
                print("Try again\n")
                text=rec(state)
                return text
    elif state==2:
        time.sleep(1)
        text=input()
        return text


dict={
    "spring":"helix",
    "circle":"sphere",
    "line":"cylinder",
    "cylinder":"cylinder",
    "helix":"helix",
    "sphere":"sphere",
    "rectangle":"rect",
    "box":"rect",
    "phoenix":"helix",
    "spear":"sphere",
    #commonly mispelled words
    "Sear":"sphere",
    "beer":"sphere",
    "Sprint":"spring"
}
#def tokeniser(tt): #experimental way to tokenise the given info
#    res=[]
#    cnt=0
#    prev=0
#    for i in range(tt.length()):
##            res[cnt++]=tt.substring(prev,i-1)

def grapher(menu_ind):
    print(menu_ind)
    print("Please tell the object to be graphed:\n")
    object=rec(menu_ind)
    while(dict.get(object,None)==None):
        print(' is not registered\n')
        object=rec(menu_ind)

        #print("Please provide position (3 arguments):\n")
        #position=int(dict[rec()])
    print("Please provide radius or size:\n")
    rd=rec(menu_ind)
    while(1):
        print("Size input\n")
        try:
            t2=sphere(radius=rd)
            break
        except:
            print(" is not a number\n")
            #temp=object(position,size)

def choicer(s):
    print(s.index)
    if(s.index==0):
        print("Please select an option\n")
    elif(s.index==2):
        grapher(2)
    else:
        grapher(1)




menu( choices=['Please select','Speech recognition','text'],bind=choicer)

print(rec (2))
#print(rec())
#tts=gTTS(text="Voice could not be recognised",lang="en")
