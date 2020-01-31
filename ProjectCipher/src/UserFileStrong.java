import java.io.*;

public class UserFileStrong {

  FileInputStream inputStream;                // place where file will be taken
  FileOutputStream outputStream;              // place where file will be saved
  String key;                                 // encryption key
  String oldExtension;                        // old extension in file
  String fileName;                            // name of file
  int sizeOfFile;                             // size of file
  byte[] allBytes;                            // array of all bytes of file

  /**
   * Constructor for strong encryption/decryption
   * @param fileName for file's name
   * @param size for file's size
   * @param key for encryption/decryption key
   */
  public UserFileStrong(String fileName, int size, String key) {
    this.fileName = fileName;
    this.sizeOfFile = size;
    this.key = key;
    this.oldExtension = "";
  }

  /**
   * Getter of file's name
   * @return name of file
   */
  public String getFileName() {
    return fileName;
  }

  /**
   * Mrthod for changing an extension in file
   * @param name for name of file
   * @param sign name of file with extension after changing
   * @return
   */
  private String changeExtension(String name, String sign) {
    String tmp = name.substring(0, name.lastIndexOf('.'));
    this.oldExtension= name.substring(name.lastIndexOf('.'));
    return tmp + sign;
  }

  /**
   * Method for changing low nibble of byte.
   * @param bits for bits of byte
   * @param charOfKey for char in key
   * @return byte after changing
   */
  private byte changeLNibble(byte bits, char charOfKey) {
    byte tempMSBits = (byte) ((bits & 0xff)& 0xf0);
    byte tempLSBits = (byte) ((bits & 0xff) & 0x0f);
    byte lSbitsOfKey =(byte) ((charOfKey & 0xff) & 0x0f);

    // XOR
    tempLSBits ^= lSbitsOfKey;
    // Instead of rotation right (in java there is no operand '>>=' )
    tempLSBits &= 0x0f;
    byte temp = (byte)(tempLSBits | tempMSBits);
    // checking that after changes, byte is not in range of control chars
    return  ( (bits>31 || bits<0) && (temp >31 || temp < 0) &&
                                  bits != 127 && temp != 127 &&
                                  bits != 255 && temp != 255 )? temp : bits;
  }

  /**
   * Method for changing high nibble of byte.
   * @param bits for bits of byte
   * @param charOfKey for char in key
   * @return byte after changing
   */
  private byte changeHNibble(byte bits, char charOfKey) {
    byte tempMSBits = (byte) ((bits & 0xff)& 0xf0);
    byte tempLSBits = (byte) ((bits & 0xff) & 0x0f);
    byte lSbitsOfKey =(byte) ((charOfKey & 0xff) & 0x0f);

    // Rotation left
    lSbitsOfKey <<= 4;
    // XOR
    tempMSBits ^= lSbitsOfKey;
    byte temp = (byte)(tempLSBits | tempMSBits);
    // checking that after changes, byte is not in range of control chars
    return  ( (bits>31 || bits<0) && (temp >31 || temp < 0) &&
                                  bits != 127 && temp != 127 &&
                                  bits != 255 && temp != 255 )? temp : bits;
  }

  /**
   * Method for making XORNOT on byte
   * @param bits for bits of byte
   * @param charOfKey for char in key
   * @return byte after changing
   */
  private byte xorNot(byte bits, char charOfKey) {
    byte temp = (byte) ( (~((bits & 0xff) ^ (charOfKey & 0xff))) & 0xff);
    // checking that after changes, byte is not in range of control chars
    return  (( (bits>31 || bits<0) && (temp >31 || temp < 0) &&
                                   bits != 127 && temp != 127 &&
                                   bits != 255 && temp != 255 )? temp : bits);
  }

  /**
   * Method for changing middle nibble (4 middle bits) in byte
   * @param bits for bits of byte
   * @param charOfKey for char in key
   * @return byte after changing
   */
  private byte changeMiddle(byte bits, char charOfKey) {
    byte tempMiddle = (byte) ((bits & 0xff)& 0x3c);
    byte tempFarthest = (byte) ((bits & 0xff) & 0xc3);
    byte MiddleBitsOfKey =(byte) ((charOfKey & 0xff) & 0x3c);

    // XOR
    tempMiddle ^= MiddleBitsOfKey;
    byte temp = (byte)((tempFarthest & 0xff) | tempMiddle);
    // checking that after changes, byte is not in range of control chars
    return  ( (bits>31 || bits<0) && (temp >31 || temp < 0) &&
                                  bits != 127 && temp != 127 &&
                                  bits != 255 && temp != 255 )? temp : bits;
  }

  /**
   * Method for changing 2 MS bits and 2 LS bits
   * @param bits for bits of byte
   * @param charOfKey for char in key
   * @return byte after changing
   */
  private byte changeFarthest(byte bits, char charOfKey) {
    byte tempMiddle = (byte) ((bits & 0xff)& 0x3c);
    byte tempFarthest = (byte) ((bits & 0xff) & 0xc3);
    byte FarthestBitsOfKey =(byte) ((charOfKey & 0xff) & 0xc3);

    tempFarthest = (byte) ((tempFarthest & 0xff) ^ (FarthestBitsOfKey & 0xff));
    byte temp = (byte)((tempFarthest & 0xff) | tempMiddle);
    // checking that after changes, byte is not in range of control chars
    return  ( (bits>31 || bits<0) && (temp >31 || temp < 0) &&
                                  bits != 127 && temp != 127 &&
                                  bits != 255 && temp != 255 )? temp : bits;
  }

  /**
   * Method for counting bit '1' in byte
   * @param bits for bits in byte
   * @return amount of '1' in byte
   */
  private int countBits(char bits) {
    int count=0;
    byte temp;
    for(int i=0; i<8; i++) {
      temp= (byte) ((bits & 0xff) & (10^i));
      count += temp >>> i;
    }
    return count;
  }

  /**
   * Method that makes choice which algorithm method to use,
   * using a char of key
   * @param choice for algorithm
   * @param charOfKey for char of key
   */
  private void ChoiceByChar(int choice, char charOfKey) {
    for(int i = 0; i < this.allBytes.length - 1; i++) {
      switch (choice) {
        case 1: allBytes[i] = xorNot(allBytes[i], charOfKey);
          break;
        case 2: allBytes[i] = changeHNibble(allBytes[i], charOfKey);
          break;
        case 3: allBytes[i] = changeLNibble(allBytes[i], charOfKey);
          break;
        case 4: allBytes[i] = changeMiddle(allBytes[i], charOfKey);
          break;
        case 5: allBytes[i] = changeFarthest(allBytes[i], charOfKey);
          break;
      }
    }
  }

  /**
   * Method for strong encryption.
   * Taking user's key and making encryption
   * by every char that is in key.
   */
  public void strongEncrypt() throws Exception {

    this.inputStream = new FileInputStream("C:/xampp/htdocs/Cipher/upload/" + fileName);
    this.outputStream = new FileOutputStream("C:/xampp/htdocs/Cipher/upload/" + changeExtension(this.fileName, ".seqr"));
    this.allBytes = new byte[this.sizeOfFile + this.oldExtension.length() + 3];
    inputStream.read(allBytes);
    inputStream.close();

    this.insertData();
    //**** encryption code ****//
    char tempChar;

    for(int i = 0; i < this.key.length(); i++) {
      tempChar = this.key.charAt(i);

      // if char in key is digit, we use XORNOT encryption algorithm
      if(tempChar >= '0' && tempChar <= '9')
        ChoiceByChar(1, tempChar);

      // if char in key is uppercase letter, we use changeHNibble encryption algorithm
      else if(tempChar >= 'A' && tempChar <= 'Z')
        ChoiceByChar(2, tempChar);

      // if char in key is lowercase letter, we use changeLNibble encryption algorithm
      else if(tempChar >= 'a' && tempChar <= 'z')
        ChoiceByChar(3, tempChar);

      // if amount of '1' in char's byte is even, we use changeMiddle encryption algorithm
      else if(countBits(this.key.charAt(i))% 2 == 0)
        ChoiceByChar(4, tempChar);

      // if amount of '1' in char's byte is odd, we use changeFarthest encryption algorithm
      else
        ChoiceByChar(5, tempChar);
    }
    outputStream.write(allBytes);
    outputStream.close();
  }

  /**
   * Method for hiding an extension in file,
   * length of extension, type of encryption
   */
  private void insertData() {
    this.allBytes[allBytes.length - 1] = (byte)49;
    allBytes[allBytes.length - 2] = (byte) ((this.oldExtension).length() % 10 + '0');
    allBytes[allBytes.length - 3] = (byte) ((this.oldExtension).length() / 10 + '0');

    for(int i = 0; i < this.oldExtension.length(); i++) {
      allBytes[allBytes.length - 4 - i] = (byte)  (this.oldExtension.charAt(i));
    }
  }

  /**
   * Method for strong decryption.
   * Taking user's key and making decryption
   * by every char from the end of the key.
   */
  public void strongDecrypt() throws Exception {
    this.inputStream = new FileInputStream("C:/xampp/htdocs/Cipher/upload/" + this.fileName);
    this.allBytes = new byte[sizeOfFile];
    this.inputStream.read(allBytes);
    this.inputStream.close();

    //**** decryption code ****//
    char tempChar;

    for(int i = this.key.length()-1; i >= 0 ; i--) {
      tempChar = this.key.charAt(i);

      // if char in key is digit, we use XORNOT encryption algorithm
      if(tempChar >= '0' && tempChar <= '9')
        ChoiceByChar(1, tempChar);

      // if char in key is uppercase letter, we use changeHNibble encryption algorithm
      else if(tempChar >= 'A' && tempChar <= 'Z')
        ChoiceByChar(2, tempChar);

      // if char in key is lowercase letter, we use changeLNibble encryption algorithm
      else if(tempChar >= 'a' && tempChar <= 'z')
        ChoiceByChar(3, tempChar);

      // if amount of '1' in char's byte is even, we use changeMiddle encryption algorithm
      else if(countBits(this.key.charAt(i))%2==0)
        ChoiceByChar(4, tempChar);

      // if amount of '1' in char's byte is odd, we use changeFarthest encryption algorithm
      else
        ChoiceByChar(5, tempChar);
    }

    this.fileName = changeExtension(this.fileName, findOldExtension());
    this.outputStream = new FileOutputStream("C:/xampp/htdocs/Cipher/upload/" + this.fileName);
    this.deleteData();

    this.outputStream.write(allBytes);
    this.outputStream.close();
  }

  /**
   * Method for searching an old extension of file
   * @return old extension of file
   */
  private String findOldExtension () {
    int tempSize = (this.allBytes[this.allBytes.length - 2] - 48) + ((this.allBytes[this.allBytes.length - 3] - 48) * 10);
    for(int i = 0; i < tempSize; i++)
      this.oldExtension +=  (char) allBytes[allBytes.length - (4 + i)];

    return this.oldExtension;
  }

  /**
   * Method for deleting old extension in file,
   * size of extension and type of encryption
   */
  private void deleteData() {
    byte[] temp = new byte[allBytes.length - this.oldExtension.length() - 3];
    for(int i = 0; i < temp.length; i++)
      temp[i] = allBytes[i];

    this.allBytes = temp;
  }
}
