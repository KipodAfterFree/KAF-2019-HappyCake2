# HappyCake2

HappyCake2 is an information security challenge in the Web category, and was presented to participants of [KAF CTF 2019](https://ctf.kipodafterfree.com)

## Challenge story

Who needs cakes these days - Mini Cakes(TM) are way more compact.

## Challenge exploit

Filtering failure

## Challenge solution

No need

## Building and installing

[Clone](https://github.com/NadavTasher/2019-HappyCake/archive/master.zip) the repository, then type the following command to build the container:
```bash
docker build . -t happycake2
```

To run the challenge, execute the following command:
```bash
docker run --rm -d -p 1170:80 happycake2
```

## Usage

You may now access the challenge interface through your browser: `http://localhost:1170`

## Flag

Flag is:
```flagscript
KAF{_w00ps_f0rg0t_t0_d0_s3cur1ty}
```

## License
[MIT License](https://choosealicense.com/licenses/mit/)