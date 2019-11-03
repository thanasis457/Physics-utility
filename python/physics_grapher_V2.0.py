from vpython import*
def rec(announce):
    text=input(announce)
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
def grapher():
    announce="Please tell the object to be graphed:"
    while(1):
        object=rec(announce+"\n")
        try:
            dict[object]
            break
        except:
            announce=object+" is not registered"
    announce="Please provide radius or size:"
    while(1):
        rd=rec(announce+"\n")
        v=False
        for i in range(len(rd)):
            if(not((ord(rd[i])-ord("0")>=0 and ord(rd[i])-ord("0")<=9))):
                v=True
        if not(v):
            t2=sphere(radius=int(rd))
            break
        else:
            announce=(rd+" is not a number")
    return t2

grapher()
