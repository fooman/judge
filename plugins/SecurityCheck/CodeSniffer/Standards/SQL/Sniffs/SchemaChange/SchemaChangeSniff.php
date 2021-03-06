<?php
class SQL_Sniffs_SchemaChange_SchemaChangeSniff implements PHP_CodeSniffer_Sniff {

    protected $_forbidden = array(
        'ALTER TABLE',
        'CREATE TABLE',
        'DROP TABLE',
    );
    
    public function register()
    {
        return array(T_DOUBLE_QUOTED_STRING, T_CONSTANT_ENCAPSED_STRING);
    }
    
    protected function _getPath(PHP_CodeSniffer_File $phpcsFile)
    {
        return str_replace('\\', '/', $phpcsFile->getFilename());
    }
    
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        if (preg_match('/app\/code\/.+\/sql\/.+(upgrade-|install-).+php/', $this->_getPath($phpcsFile)) !== 1) {
            foreach ($this->_forbidden as $pattern) {
                if (stripos($tokens[$stackPtr]['content'], $pattern) !== false) {
                    $phpcsFile->addError($pattern, $stackPtr);
                }
            }
        }
    }
}
?>
