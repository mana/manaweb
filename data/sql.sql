-- testuser is the account name, and the password is test
-- mail is test@test.com

CREATE TABLE tmw_accounts (
    id              INTEGER     PRIMARY KEY,
    username        TEXT        NOT NULL UNIQUE,
    password        TEXT        NOT NULL,
    email           TEXT        NOT NULL UNIQUE,
    level           INTEGER     NOT NULL,
    banned          INTEGER     NOT NULL,
    activation      TEXT        NULL );

 
/*  ruby: 
    --------
    crypt pwd:          Digest.hexencode( Digest::SHA256.digest("admintest")  ) 
    current time:       Time.now.to_f
    
    
    demo users:
    --------------
    
    username            password            email
    =================== =================== =======================
    testuser            test                test@test.com
    banned_user         test                test@test.com
    gm                  test                test@test.com
    admin               test                test@test.com
*/

INSERT INTO TMW_ACCOUNTS VALUES( 1, 'testuser',    'ccb200748a9304f5a2436e6f4039362144bf95fbfb391b451a5481d5deb964cd', 'f660ab912ec121d1b1e928a0bb4bc61b15f5ad44d5efdc4e1c92a25e99b8e44a', 10, 0 );
INSERT INTO TMW_ACCOUNTS VALUES( 2, 'banned_user', 'c4b3c6d725254d7712e2dc3ec7e811c159e46c4ad96ade007b0ddbd14b52694d', 'f660ab912ec121d1b1e928a0bb4bc61b15f5ad44d5efdc4e1c92a25e99b8e44a',  0, 1219935729 );
INSERT INTO TMW_ACCOUNTS VALUES( 3, 'gm',          '1164a8c1f57c3e3738d7836aaee13b2953049352d78f8e2fd1f62d7feefa2d49', 'f660ab912ec121d1b1e928a0bb4bc61b15f5ad44d5efdc4e1c92a25e99b8e44a', 50, 0 );
INSERT INTO TMW_ACCOUNTS VALUES( 4, 'admin',       'bbd7182cd0ee95488f1a1e6f3fe0d8f94ed0d14e4db1dce713fe82a3231c523d', 'f660ab912ec121d1b1e928a0bb4bc61b15f5ad44d5efdc4e1c92a25e99b8e44a', 99, 0 );


CREATE TABLE tmw_characters (
    id              INTEGER     PRIMARY KEY,
    user_id         INTEGER     NOT NULL,
    name            TEXT        NOT NULL UNIQUE,
    gender          INTEGER     NOT NULL,
    hair_style      INTEGER     NOT NULL,
    hair_color      INTEGER     NOT NULL,
    level           INTEGER     NOT NULL,
    char_pts        INTEGER     NOT NULL,
    correct_pts     INTEGER     NOT NULL,
    money           INTEGER     NOT NULL,
    x               INTEGER     NOT NULL,
    y               INTEGER     NOT NULL,
    map_id          INTEGER     NOT NULL,
    str             INTEGER     NOT NULL,
    agi             INTEGER     NOT NULL,
    dex             INTEGER     NOT NULL,
    vit             INTEGER     NOT NULL,
    int             INTEGER     NOT NULL,
    will            INTEGER     NOT NULL,
    unarmed_exp     INTEGER     NOT NULL,
    knife_exp       INTEGER     NOT NULL,
    sword_exp       INTEGER     NOT NULL,
    polearm_exp     INTEGER     NOT NULL,
    staff_exp       INTEGER     NOT NULL,
    whip_exp        INTEGER     NOT NULL,
    bow_exp         INTEGER     NOT NULL,
    shoot_exp       INTEGER     NOT NULL,
    mace_exp        INTEGER     NOT NULL,
    axe_exp         INTEGER     NOT NULL,
    thrown_exp      INTEGER     NOT NULL,
    FOREIGN KEY (user_id) REFERENCES tmw_accounts(id),
    FOREIGN KEY (map_id)  REFERENCES tmw_maps(id));

--                                                     Gener Hair Lvl Pts   Money  Pos
INSERT INTO TMW_CHARACTERS VALUES(  1, 1, 'testuser',  0,    1,9, 1,  0,0,      0, 802,592,1, 10,10,10,10,10,10, 0,0,0,0,0,0,0,0,0,0,0 );
INSERT INTO TMW_CHARACTERS VALUES(  2, 1, 'testuser2', 1,    1,9, 8,  0,0,  30100, 803,592,1, 11,11,11,11,11,11, 0,0,0,0,0,0,0,0,0,0,0 );

CREATE TABLE tmw_inventories (
    id       INTEGER  PRIMARY KEY,
    owner_id INTEGER  NOT NULL,
    slot     INTEGER  NOT NULL,
    class_id INTEGER  NOT NULL,
    amount   INTEGER  NOT NULL,
    FOREIGN KEY (owner_id) REFERENCES tmw_characters(id));
    
CREATE TABLE tmw_guilds (
    id      INTEGER     PRIMARY KEY,
    name    TEXT        NOT NULL UNIQUE
);

INSERT INTO TMW_GUILDS VALUES( 1, 'Masters of the universe' );
INSERT INTO TMW_GUILDS VALUES( 2, 'Guild Name 2' );
INSERT INTO TMW_GUILDS VALUES( 3, 'Guild Name 3' );
INSERT INTO TMW_GUILDS VALUES( 4, 'Guild Name 4' );
INSERT INTO TMW_GUILDS VALUES( 5, 'Guild Name 5' );


CREATE TABLE tmw_guild_members (
    guild_id       INTEGER     NOT NULL,
    member_name    TEXT        NOT NULL,
    FOREIGN KEY (guild_id)    REFERENCES tmw_guilds(id),
    FOREIGN KEY (member_name) REFERENCES tmw_characters(name)
);

CREATE TABLE tmw_quests (
    owner_id INTEGER NOT NULL,
    name     TEXT    NOT NULL,
    value    TEXT    NOT NULL,
    FOREIGN KEY (owner_id) REFERENCES tmw_characters(id)
);
