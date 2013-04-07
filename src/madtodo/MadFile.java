package madtodo;

import java.io.File;
import java.io.IOException;
import java.io.RandomAccessFile;
import java.nio.ByteBuffer;
import java.nio.channels.FileChannel;

public class MadFile {
    public static String readFileToString(String filename)
            throws IOException {
        StringBuilder sb = new StringBuilder(1024 << 6);

        ByteBuffer bbuff = ByteBuffer.allocate(1024);
        RandomAccessFile raf = new RandomAccessFile(new File(filename), "r");
        FileChannel fc = raf.getChannel();

        while (fc.read(bbuff) != -1) {
            bbuff.flip();
            while (bbuff.hasRemaining()) {
                sb.append((char) bbuff.get());
            }
            bbuff.clear();
        }
        raf.close();

        return sb.toString();
    }
}
