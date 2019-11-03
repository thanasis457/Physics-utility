from PIL import Image
import pytesseract
path_to_image=input("Give path to image:\n")
text=pytesseract.image_to_string(path_to_image)
print(text)
